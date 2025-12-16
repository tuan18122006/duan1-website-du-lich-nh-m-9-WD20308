<?php
class BookingModel extends Model
{
    // =================================================================
    // 1. CÁC HÀM CƠ BẢN (Lấy danh sách, Tạo mới, Chi tiết)
    // =================================================================

    // Lấy danh sách booking (Admin)
    public function getAllBookings()
    {
        $sql = "SELECT 
                    b.id, b.customer_name, b.customer_phone, b.people,
                    b.total_price, b.status, b.start_date, t.tour_name 
                FROM bookings b
                LEFT JOIN tours t ON b.tour_id = t.tour_id
                ORDER BY b.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy chi tiết booking
    public function getBookingById($id)
    {
        $sql = "SELECT b.*, t.tour_name, t.tour_type,
                    COALESCE(g_custom.full_name, g_standard.full_name, 'Chưa phân công') as guide_name,
                    COALESCE(g_custom.phone, g_standard.phone, '') as guide_phone
                FROM bookings b
                JOIN tours t ON b.tour_id = t.tour_id
                LEFT JOIN guides g_custom ON t.guide_id = g_custom.guide_id
                LEFT JOIN tour_schedules s ON b.schedule_id = s.schedule_id
                LEFT JOIN guides g_standard ON s.guide_id = g_standard.guide_id
                WHERE b.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Tạo booking mới
    public function createBooking($data)
    {
        $sql = "INSERT INTO bookings (
                    tour_id, schedule_id, customer_name, customer_phone, 
                    customer_email, customer_address, people, total_price, 
                    start_date, status, note
                ) VALUES (
                    :tour_id, :schedule_id, :customer_name, :customer_phone, 
                    :customer_email, :customer_address, :people, :total_price, 
                    :start_date, 'Chờ xử lý', :note
                )";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // =================================================================
    // 2. CÁC HÀM HỖ TRỢ (Update, Đếm chỗ, Feedback...)
    // =================================================================

    public function getBookedSeats($tour_id)
    {
        $sql = "SELECT SUM(people) as total_booked FROM bookings WHERE tour_id = :tour_id AND status != 'Đã hủy'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        $result = $stmt->fetch();
        return $result['total_booked'] ?? 0;
    }

    public function updateAllBookingsStatus($tour_id, $status)
    {
        $sql = "UPDATE bookings SET status = :status WHERE tour_id = :tour_id AND status != 'Đã hủy'";
        return $this->db->prepare($sql)->execute([':status' => $status, ':tour_id' => $tour_id]);
    }

    public function updateTourGuide($tour_id, $guide_id)
    {
        $sql = "UPDATE tours SET guide_id = :guide_id WHERE tour_id = :tour_id";
        return $this->db->prepare($sql)->execute([':guide_id' => $guide_id, ':tour_id' => $tour_id]);
    }

    public function saveFeedback($data)
    {
        $sql = "INSERT INTO guide_feedbacks (booking_id, guide_id, user_id, content) VALUES (:booking_id, :guide_id, :user_id, :content)";
        return $this->db->prepare($sql)->execute($data);
    }

    public function getBookingsByTourId($tour_id)
    {
        $sql = "SELECT b.*, t.tour_name FROM bookings b JOIN tours t ON b.tour_id = t.tour_id WHERE b.tour_id = :tour_id ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }

    public function updateBookingStatus($id, $status)
    {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function getBookingsByType($tour_type = 0, $keyword = null, $date = null) {
        $sql = "SELECT b.id, b.customer_name, b.customer_phone, b.people, b.total_price, b.status, b.start_date, t.tour_name
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.tour_id 
                WHERE t.tour_type = :tour_type";
        
        $params = [':tour_type' => $tour_type];

        if ($keyword) {
            $sql .= " AND (b.customer_name LIKE :kw OR b.customer_phone LIKE :kw OR t.tour_name LIKE :kw)";
            $params[':kw'] = "%$keyword%";
        }

        if ($date) {
            // Tìm theo ngày khởi hành
            $sql .= " AND DATE(b.start_date) = :date";
            $params[':date'] = $date;
        }

        $sql .= " ORDER BY b.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // =================================================================
    // 3. QUẢN LÝ HÀNH KHÁCH & ĐIỂM DANH (CHECK-IN)
    // =================================================================

    // Thêm danh sách hành khách lúc đặt tour
    public function addPassengers($booking_id, $schedule_id, $passengers)
    {
        if (!$booking_id || !$schedule_id) return false;

        $sql = "INSERT INTO booking_passengers (
                booking_id, schedule_id, full_name, gender, age, is_present, is_booker
            ) VALUES (
                :booking_id, :schedule_id, :full_name, :gender, :age, 0, :is_booker
            )";

        $stmt = $this->db->prepare($sql);

        foreach ($passengers as $p) {
            $age = ($p['age'] === '') ? null : ($p['age'] ?? null);
            $is_booker = $p['is_booker'] ?? false;
            try {
                $stmt->execute([
                    ':booking_id' => $booking_id,
                    ':schedule_id' => $schedule_id,
                    ':full_name'  => $p['name'] ?? 'Khách',
                    ':gender'     => $p['gender'] ?? 'Chưa rõ',
                    ':age'        => $age,
                    ':is_booker'  => $is_booker ? 1 : 0
                ]);
            } catch (Exception $e) {
                error_log("Lỗi thêm hành khách: " . $e->getMessage());
                return false;
            }
        }
        return true;
    }

    // Lấy danh sách khách theo Tour (Admin xem)
    public function getPassengersByTour($tour_id)
    {
        $sql = "SELECT bp.full_name, bp.gender, bp.age, b.customer_phone, b.status, s.start_date, b.customer_name as booker_name 
            FROM booking_passengers bp
            JOIN bookings b ON bp.booking_id = b.id
            LEFT JOIN tour_schedules s ON b.schedule_id = s.schedule_id
            WHERE b.tour_id = :tour_id AND b.status != 'Đã hủy'
            ORDER BY s.start_date ASC, b.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }

public function getPassengersBySchedule($schedule_id)
    {
        // Thêm b.status vào dòng SELECT dưới đây
        $sql = "SELECT 
            bp.id AS passenger_id, 
            bp.full_name, 
            bp.gender,         
            bp.age,           
            bp.is_present,  
            bp.is_booker, 
            b.customer_phone,
            b.status,  /* <--- THÊM DÒNG NÀY */
            b.customer_name AS booker_name
        FROM booking_passengers bp
        JOIN bookings b ON bp.booking_id = b.id
        WHERE bp.schedule_id = :schedule_id AND b.status != 'Đã hủy'
        ORDER BY b.id ASC"; 
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':schedule_id' => $schedule_id]);
        return $stmt->fetchAll();
    }

    // Cập nhật trạng thái điểm danh
    public function updateCheckinStatus($schedule_id, $present_passenger_ids)
    {
        $this->db->beginTransaction();
        try {
            // 1. Reset: Đặt TẤT CẢ hành khách của lịch trình này thành VẮNG MẶT (0)
            $sql_reset = "UPDATE booking_passengers SET is_present = 0 WHERE schedule_id = :schedule_id";
            $stmt_reset = $this->db->prepare($sql_reset);
            $stmt_reset->execute([':schedule_id' => $schedule_id]);

            // 2. Update: Đặt những hành khách được tick thành CÓ MẶT (1)
            if (!empty($present_passenger_ids)) {
                $placeholders = implode(',', array_fill(0, count($present_passenger_ids), '?'));
                $sql_update = "UPDATE booking_passengers SET is_present = 1 WHERE schedule_id = ? AND id IN ($placeholders)"; 

                $params = array_merge([$schedule_id], $present_passenger_ids);

                $stmt_update = $this->db->prepare($sql_update);
                $stmt_update->execute($params);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Lỗi transaction điểm danh: " . $e->getMessage());
            return false;
        }
    }

    // --- [BỔ SUNG] HÀM THỐNG KÊ ĐIỂM DANH (Dùng cho OperationController) ---
    public function getCheckInStats($schedule_id) {
        // Đếm tổng số khách (trong các booking không bị hủy)
        $sqlTotal = "SELECT COUNT(*) FROM booking_passengers bp 
                     JOIN bookings b ON bp.booking_id = b.id 
                     WHERE b.schedule_id = :sid AND b.status != 'Đã hủy'";
        
        // Đếm số khách ĐÃ CÓ MẶT (is_present = 1)
        $sqlChecked = "SELECT COUNT(*) FROM booking_passengers bp 
                       JOIN bookings b ON bp.booking_id = b.id 
                       WHERE b.schedule_id = :sid AND bp.is_present = 1 AND b.status != 'Đã hủy'";

        $stmtTotal = $this->db->prepare($sqlTotal); 
        $stmtTotal->execute([':sid' => $schedule_id]);
        
        $stmtChecked = $this->db->prepare($sqlChecked); 
        $stmtChecked->execute([':sid' => $schedule_id]);

        return [
            'total' => $stmtTotal->fetchColumn(),
            'checked' => $stmtChecked->fetchColumn()
        ];
    }
}