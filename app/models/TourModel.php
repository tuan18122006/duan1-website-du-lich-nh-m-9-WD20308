<?php

class TourModel extends Model
{
    // 1. LẤY DANH SÁCH TOUR (Chỉ lấy tour thường, Type = 0)
    public function getAllTour()
    {
        $sql = "SELECT t.*, c.category_name AS category_name 
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.category_id
                WHERE t.tour_type = 0  
                ORDER BY t.tour_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. LẤY TOUR THEO DANH MỤC
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

    // 3. LẤY TẤT CẢ DANH MỤC
    public function getAllCategories()
    {
        $sql = "SELECT * FROM tour_categories ORDER BY category_id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // 4. THÊM TOUR MỚI
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

    // 5. CẬP NHẬT TOUR
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

    // 6. XÓA TOUR
    public function deleteTour($id)
    {
        $sql = "DELETE FROM tours WHERE tour_id = :id";
        return $this->db->prepare($sql)->execute([':id' => $id]);
    }

    // 7. LẤY CHI TIẾT 1 TOUR
    public function getTourById($id)
    {
        $sql = "SELECT * FROM tours WHERE tour_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 8. LẤY DANH SÁCH HDV (ĐÃ SỬA: CHỈ LẤY NGƯỜI ĐANG LÀM VIỆC)
    public function getAllGuides()
    {
        // Thêm điều kiện WHERE work_status = 1
        $sql = "SELECT * FROM guides WHERE work_status = 1 ORDER BY guide_id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // --- PHẦN QUẢN LÝ LỊCH TRÌNH (SCHEDULES) ---

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

    public function getScheduleById($id) {
        $sql = "SELECT * FROM tour_schedules WHERE schedule_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function addSchedule($data) {
        $sql = "INSERT INTO tour_schedules (tour_id, start_date, end_date, price, stock, guide_id) 
                VALUES (:tour_id, :start_date, :end_date, :price, :stock, :guide_id)";
        
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function deleteSchedule($id) {
        $sql = "DELETE FROM tour_schedules WHERE schedule_id = :id";
        return $this->db->prepare($sql)->execute([':id' => $id]);
    }

    public function updateScheduleBooked($schedule_id, $people) {
        $sql = "UPDATE tour_schedules SET booked = booked + :people WHERE schedule_id = :schedule_id";
        return $this->db->prepare($sql)->execute([':people' => $people, ':schedule_id' => $schedule_id]);
    }

    // --- PHẦN TOUR CUSTOM & BÁO GIÁ ---
    public function getToursByType($type = 0, $keyword = null, $category_id = null) {
        $sql = "SELECT t.*, c.category_name 
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.category_id
                WHERE t.tour_type = :type";
        
        $params = [':type' => $type];

        if ($keyword) {
            $sql .= " AND t.tour_name LIKE :keyword";
            $params[':keyword'] = "%$keyword%";
        }

        if ($category_id && $category_id !== 'Tất cả') {
            $sql .= " AND t.category_id = :cat_id";
            $params[':cat_id'] = $category_id;
        }

        $sql .= " ORDER BY t.tour_id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function createCustomTour($data) {
        $sql = "INSERT INTO tours (
                        tour_name, description, category_id, image_url, 
                        duration_days, start_date, tour_type, status, base_price, people
                    ) VALUES (
                        :tour_name, :description, :category_id, :image_url,
                        :duration_days, :start_date, 1, 1, 0, :people  
                        /* Số 1 ở đây chính là tour_type = 1 (Custom) */
                    )";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId(); 
        }
        return false;
    }

    public function updateQuote($id, $price, $guide_id, $policy) {
        
        $policy = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $policy);
        // ---------------------------------------------

        $sqlTour = "UPDATE tours SET base_price = :price, policy = :policy, status = 1 WHERE tour_id = :id";
        $this->db->prepare($sqlTour)->execute([
            ':price' => $price, 
            ':policy' => $policy, 
            ':id' => $id
        ]);

        $checkSql = "SELECT schedule_id FROM tour_schedules WHERE tour_id = :id";
        $stmtCheck = $this->db->prepare($checkSql);
        $stmtCheck->execute([':id' => $id]);
        $schedule = $stmtCheck->fetch();

        if ($schedule) {
            $sqlSchedule = "UPDATE tour_schedules SET price = :price, guide_id = :guide_id WHERE tour_id = :id";
            $this->db->prepare($sqlSchedule)->execute([
                ':price' => $price, 
                ':guide_id' => $guide_id,
                ':id' => $id
            ]);
        } else {
            $tour = $this->getTourById($id);
            if ($tour) {
                $this->addSchedule([
                    ':tour_id' => $id,
                    ':start_date' => $tour['start_date'],
                    ':end_date' => $tour['start_date'],
                    ':price' => $price,
                    ':stock' => $tour['people'],
                    ':guide_id' => $guide_id
                ]);
            }
        }

        $sqlBooking = "UPDATE bookings SET total_price = people * :price, status = 'Đã xác nhận' WHERE tour_id = :id";
        $this->db->prepare($sqlBooking)->execute([':price' => $price, ':id' => $id]);
        
        return true;
    }

    public function updateTourStatus($tour_id, $status)
    {
        $sql = "UPDATE tours SET status = :status WHERE tour_id = :tour_id";
        return $this->db->prepare($sql)->execute([':status' => $status, ':tour_id' => $tour_id]);
    }

    public function getHistoryTours() {
        $sql = "SELECT t.*, c.category_name 
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.category_id
                WHERE t.status IN (0, 3) 
                ORDER BY t.end_date DESC";
        return $this->db->prepare($sql)->fetchAll();
    }

    public function getToursByGuide($guide_id, $keyword = null, $date = null) {
        $sql = "SELECT DISTINCT t.*, s.schedule_id, s.start_date as schedule_start, s.end_date as schedule_end
                FROM tours t
                JOIN tour_schedules s ON t.tour_id = s.tour_id
                WHERE s.guide_id = :guide_id";
        
        $params = [':guide_id' => $guide_id];

        // 1. Tìm theo Tên hoặc Mã
        if ($keyword) {
            $sql .= " AND (t.tour_name LIKE :kw OR t.tour_id LIKE :kw)";
            $params[':kw'] = "%$keyword%";
        }

        // 2. [THÊM MỚI] Tìm theo Ngày khởi hành
        if ($date) {
            // Dùng hàm DATE() để chỉ so sánh ngày, bỏ qua giờ phút
            $sql .= " AND DATE(s.start_date) = :date";
            $params[':date'] = $date;
        }

        $sql .= " ORDER BY s.start_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAllSchedulesWithDetails($keyword = null, $date = null) {
        $sql = "SELECT s.*, t.tour_name, t.image_url, g.full_name as guide_name,
                (SELECT COUNT(*) FROM bookings b WHERE b.schedule_id = s.schedule_id AND b.status != 'Đã hủy') as total_bookings
                FROM tour_schedules s
                JOIN tours t ON s.tour_id = t.tour_id
                LEFT JOIN guides g ON s.guide_id = g.guide_id
                WHERE 1=1"; // Mẹo: 1=1 để dễ nối chuỗi AND
        
        $params = [];

        if ($keyword) {
            $sql .= " AND (t.tour_name LIKE :keyword OR g.full_name LIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }

        if ($date) {
            $sql .= " AND DATE(s.start_date) = :date";
            $params[':date'] = $date;
        }

        $sql .= " ORDER BY s.start_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateMeetingPoint($schedule_id, $point) {
        $sql = "UPDATE tour_schedules SET meeting_point = :point WHERE schedule_id = :id";
        return $this->db->prepare($sql)->execute([':point' => $point, ':id' => $schedule_id]);
    }
// File: app/models/TourModel.php

public function checkGuideAvailability($guide_id, $new_start, $new_end) {
    
    $sql = "SELECT COUNT(*) FROM tour_schedules 
            WHERE guide_id = :guide_id 
            AND start_date < :new_end 
            AND end_date > :new_start";

    
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':guide_id' => $guide_id,
        ':new_start' => $new_start,
        ':new_end' => $new_end
    ]);
    
    $count = $stmt->fetchColumn();

    return $count == 0; 
}
}
?>