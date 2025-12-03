<?php
class BookingModel extends Model {
    
    // 1. Lấy danh sách booking (đã tối ưu)
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

    // 2. Lấy chi tiết booking
    public function getBookingById($id) {
        $sql = "SELECT b.*, 
                       t.tour_name, t.start_date as tour_start_date, t.end_date as tour_end_date, 
                       t.people as max_people, t.guide_id as tour_guide_id
                FROM bookings b
                JOIN tours t ON b.tour_id = t.tour_id
                WHERE b.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 3. Tạo booking mới
    public function createBooking($data) {
        $sql = "INSERT INTO bookings (tour_id, customer_name, customer_phone, customer_email, customer_address, people, total_price, start_date, status, note) 
                VALUES (:tour_id, :customer_name, :customer_phone, :customer_email, :customer_address, :people, :total_price, :start_date, 'Chờ xử lý', :note)";
        return $this->db->prepare($sql)->execute($data);
    }
    
    // 4. Lấy số chỗ đã đặt
    public function getBookedSeats($tour_id) {
        $sql = "SELECT SUM(people) as total_booked FROM bookings WHERE tour_id = :tour_id AND status != 'Đã hủy'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        $result = $stmt->fetch();
        return $result['total_booked'] ?? 0;
    }

    // 5. Cập nhật trạng thái tất cả booking của 1 tour (Dùng khi kích hoạt/kết thúc tour)
    public function updateAllBookingsStatus($tour_id, $status) {
        $sql = "UPDATE bookings 
                SET status = :status 
                WHERE tour_id = :tour_id AND status != 'Đã hủy'";
        
        return $this->db->prepare($sql)->execute([
            ':status' => $status, 
            ':tour_id' => $tour_id
        ]);
    }

    // 6. Cập nhật HDV cho Tour
    public function updateTourGuide($tour_id, $guide_id) {
        $sql = "UPDATE tours SET guide_id = :guide_id WHERE tour_id = :tour_id";
        return $this->db->prepare($sql)->execute([':guide_id' => $guide_id, ':tour_id' => $tour_id]);
    }

    // 7. Lưu đánh giá
    public function saveFeedback($data) {
        $sql = "INSERT INTO guide_feedbacks (booking_id, guide_id, user_id, content) 
                VALUES (:booking_id, :guide_id, :user_id, :content)";
        return $this->db->prepare($sql)->execute($data);
    }

    // 8. Lấy booking theo Tour ID
    public function getBookingsByTourId($tour_id) {
        $sql = "SELECT b.*, t.tour_name 
                FROM bookings b
                JOIN tours t ON b.tour_id = t.tour_id
                WHERE b.tour_id = :tour_id
                ORDER BY b.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }

    // --- QUAN TRỌNG: HÀM NÀY BỊ THIẾU TRƯỚC ĐÓ ---
    // 9. Cập nhật trạng thái Booking lẻ (Xác nhận/Hủy/Hoàn thành)
    public function updateBookingStatus($id, $status) {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }
}