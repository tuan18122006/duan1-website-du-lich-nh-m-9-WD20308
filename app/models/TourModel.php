<?php

class TourModel extends Model
{
    // 1. Lấy danh sách tất cả Tour
    public function getAllTour()
    {
        $sql = "SELECT t.*, c.category_name AS category_name 
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
        $sql = "SELECT t.*, c.category_name AS category_name 
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.category_id
                WHERE t.category_id = :category_id
                ORDER BY t.tour_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category_id' => $category_id]);
        return $stmt->fetchAll();
    }

    // 3. Lấy danh sách Category
    public function getAllCategories()
    {
        $sql = "SELECT * FROM tour_categories ORDER BY category_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 4. Thêm Tour Mới (ĐÃ BỎ guide_id)
    public function addTourInfo($data)
    {
        $sql = "INSERT INTO tours (
                    category_id, tour_name, short_description, description, duration_days, 
                    base_price, image_url, end_date, start_date, supplier, policy, status, people
                ) VALUES (
                    :category_id, :tour_name, :short_description, :description, :duration_days,
                    :base_price, :image_url, :end_date, :start_date, :supplier, :policy, :status, :people
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
            ':people' => $data['people']
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

    // 7. Cập nhật Tour (ĐÃ BỎ guide_id)
    public function updateTour($data)
    {
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
                people = :people
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
            ':tour_id' => $data['tour_id']
        ]);
    }

    // 8. Lấy danh sách Hướng dẫn viên
    public function getAllGuides()
    {
        $sql = "SELECT * FROM guides ORDER BY guide_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --- CÁC HÀM KHÁC GIỮ NGUYÊN (Quản lý lịch, Custom Tour, Báo giá...) ---
    
    public function getHistoryTours() {
        $sql = "SELECT t.*, c.category_name 
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.category_id
                WHERE t.status IN (0, 3) 
                ORDER BY t.end_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // QUẢN LÝ LỊCH TOUR
    public function getSchedules($tour_id) {
        $sql = "SELECT s.*, g.full_name as guide_name 
                FROM tour_schedules s
                LEFT JOIN guides g ON s.guide_id = g.guide_id
                WHERE s.tour_id = :tour_id 
                ORDER BY s.start_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }

    public function addSchedule($data) {
            $sql = "INSERT INTO tour_schedules (tour_id, start_date, end_date, price, stock, guide_id) 
                    VALUES (:tour_id, :start_date, :end_date, :price, :stock, :guide_id)";
            
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute($data)) {
                return $this->db->lastInsertId(); // TRẢ VỀ ID LỊCH TRÌNH VỪA TẠO
            }
            return false;
        }

    public function deleteSchedule($id) {
        $sql = "DELETE FROM tour_schedules WHERE schedule_id = :id";
        return $this->db->prepare($sql)->execute([':id' => $id]);
    }

    public function getScheduleById($id) {
        $sql = "SELECT * FROM tour_schedules WHERE schedule_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateScheduleBooked($schedule_id, $people) {
        $sql = "UPDATE tour_schedules SET booked = booked + :people WHERE schedule_id = :schedule_id";
        return $this->db->prepare($sql)->execute([':people' => $people, ':schedule_id' => $schedule_id]);
    }

    // TOUR TÙY CHỌN (CUSTOM)
    public function getToursByType($type = 0) {
        $sql = "SELECT t.*, c.category_name 
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.category_id
                WHERE t.tour_type = :type
                ORDER BY t.tour_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':type' => $type]);
        return $stmt->fetchAll();
    }

    public function createCustomTour($data) {
        $sql = "INSERT INTO tours (
                    tour_name, description, category_id, image_url, 
                    duration_days, start_date, tour_type, status, base_price, people
                ) VALUES (
                    :tour_name, :description, :category_id, :image_url,
                    :duration_days, :start_date, 1, 1, 0, :people
                )";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId(); 
        }
        return false;
    }

    // HÀM BÁO GIÁ
    public function updateQuote($id, $price, $guide_id, $policy) {
        // Vẫn cập nhật guide_id vào tours cho tour Custom (để lưu HDV chính)
        $sqlTour = "UPDATE tours SET base_price = :price, guide_id = :guide_id, policy = :policy, status = 1 WHERE tour_id = :id";
        $this->db->prepare($sqlTour)->execute([
            ':price' => $price, 
            ':guide_id' => $guide_id, 
            ':policy' => $policy, 
            ':id' => $id
        ]);

        $sqlSchedule = "UPDATE tour_schedules SET price = :price WHERE tour_id = :id";
        $this->db->prepare($sqlSchedule)->execute([':price' => $price, ':id' => $id]);

        $sqlBooking = "UPDATE bookings SET total_price = people * :price, status = 'Đã xác nhận' WHERE tour_id = :id";
        $this->db->prepare($sqlBooking)->execute([':price' => $price, ':id' => $id]);
        
        return true;
    }
    
    // Hàm update status tour (Kích hoạt/Kết thúc)
    public function updateTourStatus($tour_id, $status) {
        $sql = "UPDATE tours SET status = :status WHERE tour_id = :tour_id";
        return $this->db->prepare($sql)->execute([':status' => $status, ':tour_id' => $tour_id]);
    }
    public function getToursByGuide($guide_id) {
    $sql = "SELECT * FROM tours WHERE guide_id = :guide_id ORDER BY tour_id DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':guide_id' => $guide_id]);
    return $stmt->fetchAll();
}
}