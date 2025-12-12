<?php
class BookingModel extends Model
{

    // 1. Lấy danh sách booking
    public function getAllBookings()
    {
        $sql = "SELECT 
                    b.id,
                    b.customer_name,
                    b.customer_phone,
                    b.people,
                    b.total_price,
                    b.status,
                    b.start_date,
                    t.tour_name 
                FROM bookings b
                LEFT JOIN tours t ON b.tour_id = t.tour_id
                ORDER BY b.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy chi tiết booking (Logic lấy HDV)
    public function getBookingById($id)
    {
        $sql = "SELECT 
                    b.*, 
                    t.tour_name, 
                    t.tour_type,
                    -- Logic lấy tên HDV
                    COALESCE(g_custom.full_name, g_standard.full_name, 'Chưa phân công') as guide_name,
                    COALESCE(g_custom.phone, g_standard.phone, '') as guide_phone
                FROM bookings b
                JOIN tours t ON b.tour_id = t.tour_id
                
                -- 1. Tìm HDV cho Tour Custom (Lưu ở bảng tours)
                LEFT JOIN guides g_custom ON t.guide_id = g_custom.guide_id
                
                -- 2. Tìm HDV cho Tour Mặc định (Lưu ở bảng schedules)
                -- QUAN TRỌNG: Cần schedule_id ở bảng bookings để join được bảng này
                LEFT JOIN tour_schedules s ON b.schedule_id = s.schedule_id
                LEFT JOIN guides g_standard ON s.guide_id = g_standard.guide_id
                
                WHERE b.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 3. Tạo booking mới (ĐÃ CẬP NHẬT: Thêm schedule_id)
    public function createBooking($data)
    {
        $sql = "INSERT INTO bookings (
                    tour_id, 
                    schedule_id, 
                    customer_name, 
                    customer_phone, 
                    customer_email, 
                    customer_address, 
                    people, 
                    total_price, 
                    start_date, 
                    status, 
                    note
                ) VALUES (
                    :tour_id, 
                    :schedule_id, 
                    :customer_name, 
                    :customer_phone, 
                    :customer_email, 
                    :customer_address, 
                    :people, 
                    :total_price, 
                    :start_date, 
                    'Chờ xử lý', 
                    :note
                )";

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute($data)) {
            // [QUAN TRỌNG] Trả về ID vừa tạo thay vì trả về true
            return $this->db->lastInsertId();
        }
        return false;
    }

    // ... (Các hàm khác giữ nguyên: getBookedSeats, updateAllBookingsStatus, etc.) ...

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

    public function getBookingsByType($tour_type = 0)
    {
        $sql = "SELECT b.id, b.customer_name, b.customer_phone, b.people, b.total_price, b.status, b.start_date, t.tour_name
                FROM bookings b JOIN tours t ON b.tour_id = t.tour_id WHERE t.tour_type = :tour_type ORDER BY b.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_type' => $tour_type]);
        return $stmt->fetchAll();
    }
    // Trong BookingModel.php

    // CẦN SỬA: Hàm này phải nhận thêm $schedule_id
    public function addPassengers($booking_id, $schedule_id, $passengers) // <<< THÊM $schedule_id
    {
        if (!$booking_id || !$schedule_id) {
            return false;
        }

        // THÊM: is_present (0=Vắng/Chưa điểm danh) và schedule_id, is_booker
        $sql = "INSERT INTO booking_passengers (
                booking_id, 
                schedule_id, 
                full_name, 
                gender, 
                age, 
                is_present, 
                is_booker
            ) VALUES (
                :booking_id, 
                :schedule_id, 
                :full_name, 
                :gender, 
                :age, 
                0, 
                :is_booker
            )";

        $stmt = $this->db->prepare($sql);

        foreach ($passengers as $p) {
            $age = ($p['age'] === '') ? null : ($p['age'] ?? null); // Xử lý nếu $p['age'] không tồn tại
            $is_booker = $p['is_booker'] ?? false; // Lấy cờ Booker

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
                // (Nên log lỗi thay vì die() ngay lập tức)
                error_log("Lỗi thêm hành khách: " . $e->getMessage());
                return false;
            }
        }
        return true;
    }

    // Hàm lấy danh sách khách theo Tour để làm trang quản lý (Cho bước 5)
    // BookingModel.php

    public function getPassengersByTour($tour_id)
    {
        $sql = "SELECT 
                bp.full_name, 
                bp.gender, 
                bp.age, 
                b.customer_phone, 
                b.status,
                s.start_date,
                
                /* --- THÊM DÒNG NÀY --- */
                b.customer_name as booker_name 
                /* --------------------- */

            FROM booking_passengers bp
            JOIN bookings b ON bp.booking_id = b.id
            LEFT JOIN tour_schedules s ON b.schedule_id = s.schedule_id
            WHERE b.tour_id = :tour_id AND b.status != 'Đã hủy'
            ORDER BY s.start_date ASC, b.id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }
    // BookingModel.php

    public function getPassengersBySchedule($schedule_id)
    {
        $sql = "SELECT 
            bp.id AS passenger_id, 
            bp.full_name, 
            bp.gender,         
            bp.age,           
            bp.is_present, 
            bp.is_booker, 
            b.customer_phone,
            b.customer_name AS booker_name
        FROM booking_passengers bp
        JOIN bookings b ON bp.booking_id = b.id
        WHERE bp.schedule_id = :schedule_id
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':schedule_id' => $schedule_id]);
        return $stmt->fetchAll();
    }
    
    public function updateCheckinStatus($schedule_id, $present_passenger_ids)
    {

        $this->db->beginTransaction();

        try {
            // 1. Đặt TẤT CẢ hành khách của lịch trình này thành VẮNG MẶT (0)
            $sql_reset = "UPDATE booking_passengers 
                      SET is_present = 0 
                      WHERE schedule_id = :schedule_id";
            $stmt_reset = $this->db->prepare($sql_reset);
            $stmt_reset->execute([':schedule_id' => $schedule_id]);

            // 2. Đặt những hành khách đã check (có trong mảng POST) thành CÓ MẶT (1)
            if (!empty($present_passenger_ids)) {
                $placeholders = implode(',', array_fill(0, count($present_passenger_ids), '?'));
                $sql_update = "UPDATE booking_passengers 
                           SET is_present = 1 
                           WHERE schedule_id = ? AND id IN ($placeholders)"; // Dùng 'id' (passenger_id)

                // Ghép schedule_id vào đầu mảng ID
                $params = array_merge([$schedule_id], $present_passenger_ids);

                $stmt_update = $this->db->prepare($sql_update);
                $stmt_update->execute($params);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Lỗi điểm danh: " . $e->getMessage());
            return false;
        }
    }
}
