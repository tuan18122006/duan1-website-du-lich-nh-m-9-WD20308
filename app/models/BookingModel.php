<?php
class BookingModel extends Model {
    
    // 1. Lấy danh sách (BỎ JOIN customers)
public function getAllBookings() {
        // Sử dụng LEFT JOIN để luôn lấy được đơn hàng dù tour có bị xóa hay lỗi
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
                ORDER BY b.id DESC"; // Sắp xếp mới nhất lên đầu
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy chi tiết (BỎ JOIN customers)
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

    // ... (Giữ nguyên các hàm createBooking, updateBookingStatus, getBookedSeats...)
    // Đảm bảo hàm createBooking của bạn đã insert đúng vào các cột customer_name, customer_phone
    public function createBooking($data) {
        $sql = "INSERT INTO bookings (tour_id, customer_name, customer_phone, customer_email, customer_address, people, total_price, start_date, status, note) 
                VALUES (:tour_id, :customer_name, :customer_phone, :customer_email, :customer_address, :people, :total_price, :start_date, 'Chờ xử lý', :note)";
        return $this->db->prepare($sql)->execute($data);
    }
    
    // ... (Các hàm khác giữ nguyên)
    public function getBookedSeats($tour_id) {
        $sql = "SELECT SUM(people) as total_booked FROM bookings WHERE tour_id = :tour_id AND status != 'Đã hủy'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        $result = $stmt->fetch();
        return $result['total_booked'] ?? 0;
    }

    public function updateBookingStatus($id, $status) {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        return $this->db->prepare($sql)->execute([':status' => $status, ':id' => $id]);
    }

    public function updateTourGuide($tour_id, $guide_id) {
        $sql = "UPDATE tours SET guide_id = :guide_id WHERE tour_id = :tour_id";
        return $this->db->prepare($sql)->execute([':guide_id' => $guide_id, ':tour_id' => $tour_id]);
    }

    public function saveFeedback($data) {
        $sql = "INSERT INTO guide_feedbacks (booking_id, guide_id, content) VALUES (:booking_id, :guide_id, :content)";
        return $this->db->prepare($sql)->execute($data);
    }
    // FILE: app/models/BookingModel.php

// Lấy danh sách booking theo ID tour cụ thể
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

}