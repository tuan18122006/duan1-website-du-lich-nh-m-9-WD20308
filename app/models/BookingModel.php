<?php
class BookingModel extends Model {
    
    // 1. Lấy danh sách booking
    public function getAllBookings() {
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
    public function getBookingById($id) {
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
    public function createBooking($data) {
        // Thêm cột schedule_id vào câu lệnh INSERT
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
        return $this->db->prepare($sql)->execute($data);
    }
    
    // ... (Các hàm khác giữ nguyên: getBookedSeats, updateAllBookingsStatus, etc.) ...
    
    public function getBookedSeats($tour_id) {
        $sql = "SELECT SUM(people) as total_booked FROM bookings WHERE tour_id = :tour_id AND status != 'Đã hủy'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        $result = $stmt->fetch();
        return $result['total_booked'] ?? 0;
    }

    public function updateAllBookingsStatus($tour_id, $status) {
        $sql = "UPDATE bookings SET status = :status WHERE tour_id = :tour_id AND status != 'Đã hủy'";
        return $this->db->prepare($sql)->execute([':status' => $status, ':tour_id' => $tour_id]);
    }

    public function updateTourGuide($tour_id, $guide_id) {
        $sql = "UPDATE tours SET guide_id = :guide_id WHERE tour_id = :tour_id";
        return $this->db->prepare($sql)->execute([':guide_id' => $guide_id, ':tour_id' => $tour_id]);
    }

    public function saveFeedback($data) {
        $sql = "INSERT INTO guide_feedbacks (booking_id, guide_id, user_id, content) VALUES (:booking_id, :guide_id, :user_id, :content)";
        return $this->db->prepare($sql)->execute($data);
    }

    public function getBookingsByTourId($tour_id) {
        $sql = "SELECT b.*, t.tour_name FROM bookings b JOIN tours t ON b.tour_id = t.tour_id WHERE b.tour_id = :tour_id ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }

    public function updateBookingStatus($id, $status) {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function getBookingsByType($tour_type = 0) {
        $sql = "SELECT b.id, b.customer_name, b.customer_phone, b.people, b.total_price, b.status, b.start_date, t.tour_name
                FROM bookings b JOIN tours t ON b.tour_id = t.tour_id WHERE t.tour_type = :tour_type ORDER BY b.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_type' => $tour_type]);
        return $stmt->fetchAll();
    }
}