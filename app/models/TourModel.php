<?php

class TourModel extends Model
{
    // 1. Lấy danh sách tất cả Tour (JOIN với bảng tour_categories)
    public function getAllTour()
    {
        $sql = "SELECT 
                    t.*, 
                    c.category_name AS category_name 
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.category_id
                ORDER BY t.tour_id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy Tour theo Category
    public function getToursByCategoryId($category_id)
    {
        $sql = "SELECT 
                    t.*, 
                    c.category_name AS category_name 
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.category_id
                WHERE t.category_id = :category_id
                ORDER BY t.tour_id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category_id' => $category_id]);
        return $stmt->fetchAll();
    }

    // 3. Lấy danh sách Category (Cho Dropdown)
    public function getAllCategories()
    {
        $sql = "SELECT * FROM tour_categories ORDER BY category_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 4. Thêm Tour Mới (Đã cập nhật thêm guide_id theo DB mới)
    public function addTourInfo($data)
    {
        $sql = "INSERT INTO tours (
                    category_id, tour_name, short_description, description, duration_days, 
                    base_price, image_url, end_date, start_date, supplier, policy, status, people, guide_id
                ) VALUES (
                    :category_id, :tour_name, :short_description, :description, :duration_days,
                    :base_price, :image_url, :end_date, :start_date, :supplier, :policy, :status, :people, :guide_id
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':tour_name' => $data['tour_name'],
            ':short_description' => $data['short_description'],
            ':description' => $data['description'],
            ':duration_days' => $data['duration_days'],
            ':base_price' => $data['base_price'],
            ':image_url' => $data['image_url'],
            ':end_date' => $data['end_date'],
            ':start_date' => $data['start_date'],
            ':supplier' => $data['supplier'],
            ':policy' => $data['policy'],
            ':status' => $data['status'],
            ':people' => $data['people'],
            ':guide_id' => $data['guide_id'] ?? null // Thêm guide_id (có thể null)
        ]);
    }

    // 5. Xóa Tour
    public function deleteTour($id)
    {
        $sql = "DELETE FROM tours WHERE tour_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // 6. Lấy 1 Tour theo ID
    public function getTourById($id)
    {
        $sql = "SELECT * FROM tours WHERE tour_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 7. Cập nhật Tour (Đã cập nhật thêm guide_id theo DB mới)
    public function updateTour($data) {
        // Sử dụng tên cột chính xác theo Database bạn đã tạo
        $sql = "UPDATE tours SET 
                category_id = :category_id,
                tour_name = :tour_name,          
                short_description = :short_description,
                description = :description,
                duration_days = :duration_days,
                base_price = :base_price,       
                image_url = :image_url,         
                end_date = :end_date,
                start_date = :start_date,
                supplier = :supplier,
                policy = :policy,
                status = :status,           
                people = :people,
                guide_id = :guide_id
                WHERE tour_id = :tour_id";
                
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':tour_name' => $data['tour_name'],
            ':short_description' => $data['short_description'],
            ':description' => $data['description'],
            ':duration_days' => $data['duration_days'],
            ':base_price' => $data['base_price'],
            ':image_url' => $data['image_url'],
            ':end_date' => $data['end_date'],
            ':start_date' => $data['start_date'],
            ':supplier' => $data['supplier'],
            ':policy' => $data['policy'],
            ':status' => $data['status'],
            ':people' => $data['people'],
            ':guide_id' => $data['guide_id'] ?? null,
            ':tour_id' => $data['tour_id']
        ]);
    }

    // 8. Lấy danh sách Hướng dẫn viên
    public function getAllGuides() {
        $sql = "SELECT * FROM guides ORDER BY guide_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getToursWithBookingStatus() {
    $sql = "SELECT 
                t.*,
                t.people as max_people,
                IFNULL(SUM(b.people), 0) as current_people,
                COUNT(b.id) as total_bookings
            FROM tours t
            LEFT JOIN bookings b ON t.tour_id = b.tour_id AND b.status != 'Đã hủy'
            GROUP BY t.tour_id
            ORDER BY t.start_date DESC";
            
    return $this->db->prepare($sql)->fetchAll();
}

// Hàm cập nhật trạng thái hoạt động của Tour (Kích hoạt/Hủy)
public function updateTourStatus($tour_id, $status) {
    // status: 1=Đang nhận khách, 2=Đã đủ người/Hoạt động, 3=Đã xong, 0=Hủy
    $sql = "UPDATE tours SET status = :status WHERE tour_id = :tour_id";
    return $this->db->prepare($sql)->execute([':status' => $status, ':tour_id' => $tour_id]);
}
}