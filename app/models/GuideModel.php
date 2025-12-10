<?php
class GuideModel extends Model
{

    // Lấy danh sách HDV (JOIN 2 bảng)
    public function getAllGuides()
    {
        $sql = "SELECT u.*, g.* FROM users u 
                JOIN guides g ON u.user_id = g.user_id 
                WHERE u.role = 2 
                ORDER BY u.user_id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Lấy chi tiết 1 HDV
    public function getGuideById($user_id)
    {
        $sql = "SELECT u.*, g.experience_years, g.languages, g.guide_id 
                FROM users u 
                LEFT JOIN guides g ON u.user_id = g.user_id 
                WHERE u.user_id = :id AND u.role = 2";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $user_id]);
        return $stmt->fetch();
    }

    // Thêm mới (Transaction)
    public function createGuide($dataUser, $dataGuide)
    {
        try {
            $this->db->beginTransaction();

            // 1. Insert User
            $sqlUser = "INSERT INTO users (username, password, full_name, email, phone, role, birthday, avatar) 
                        VALUES (:username, :password, :full_name, :email, :phone, 2, :birthday, :avatar)";
            $stmtUser = $this->db->prepare($sqlUser);
            $stmtUser->execute($dataUser);

            $user_id = $this->db->lastInsertId();

            // 2. Insert Guide
            $sqlGuide = "INSERT INTO guides (user_id, full_name, date_of_birth, phone, email, experience_years, languages, avatar) 
                         VALUES (:user_id, :full_name, :birthday, :phone, :email, :exp, :lang, :avatar)";
            $stmtGuide = $this->db->prepare($sqlGuide);
            $stmtGuide->execute([
                ':user_id' => $user_id,
                ':full_name' => $dataUser[':full_name'],
                ':birthday' => $dataUser[':birthday'],
                ':phone' => $dataUser[':phone'],
                ':email' => $dataUser[':email'],
                ':exp' => $dataGuide['experience_years'],
                ':lang' => $dataGuide['languages'],
                ':avatar' => $dataUser[':avatar']
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Cập nhật (Transaction)
    public function updateGuide($user_id, $dataUser, $dataGuide)
    {
        try {
            $this->db->beginTransaction();

            // 1. Update User
            $sqlUser = "UPDATE users SET full_name=:name, email=:email, phone=:phone, birthday=:dob, avatar=:ava";
            if (!empty($dataUser['password'])) {
                $sqlUser .= ", password=:pass";
            }
            $sqlUser .= " WHERE user_id=:uid";

            $paramsUser = [
                ':name' => $dataUser['full_name'],
                ':email' => $dataUser['email'],
                ':phone' => $dataUser['phone'],
                ':dob' => $dataUser['birthday'],
                ':ava' => $dataUser['avatar'],
                ':uid' => $user_id
            ];
            if (!empty($dataUser['password'])) {
                $paramsUser[':pass'] = $dataUser['password'];
            }
            $this->db->prepare($sqlUser)->execute($paramsUser);

            // 2. Update Guide
            $sqlGuide = "UPDATE guides SET full_name=:name, email=:email, phone=:phone, date_of_birth=:dob, 
                         experience_years=:exp, languages=:lang, avatar=:ava 
                         WHERE user_id=:uid";

            $this->db->prepare($sqlGuide)->execute([
                ':name' => $dataUser['full_name'],
                ':email' => $dataUser['email'],
                ':phone' => $dataUser['phone'],
                ':dob' => $dataUser['birthday'],
                ':exp' => $dataGuide['experience_years'],
                ':lang' => $dataGuide['languages'],
                ':ava' => $dataUser['avatar'],
                ':uid' => $user_id
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    // Thêm vào class GuideModel trong file GuideModel.php

    // 1. Thống kê tổng quan cho HDV theo thời gian
    public function getGuideStats($guide_id, $startDate, $endDate)
    {
        // Tính tổng số tour được phân công
        $sqlTours = "SELECT COUNT(*) as total_tours 
                     FROM tour_schedules 
                     WHERE guide_id = :guide_id 
                     AND start_date BETWEEN :start AND :end";

        // Tính số tour đã hoàn thành (Ngày về < Hiện tại)
        $sqlCompleted = "SELECT COUNT(*) as completed_tours 
                         FROM tour_schedules 
                         WHERE guide_id = :guide_id 
                         AND end_date < NOW()
                         AND start_date BETWEEN :start AND :end";

        // Tính tổng doanh thu (Tổng giá trị các booking thuộc lịch trình do HDV này dẫn)
        // Lưu ý: Chỉ tính các booking đã xác nhận hoặc hoàn thành
        $sqlRevenue = "SELECT SUM(b.total_price) as total_revenue, SUM(b.people) as total_guests
                       FROM bookings b
                       JOIN tour_schedules s ON b.schedule_id = s.schedule_id
                       WHERE s.guide_id = :guide_id 
                       AND b.status IN ('Đã xác nhận', 'Hoàn thành')
                       AND s.start_date BETWEEN :start AND :end";

        $stmtTours = $this->db->prepare($sqlTours);
        $stmtTours->execute([':guide_id' => $guide_id, ':start' => $startDate, ':end' => $endDate]);
        $tours = $stmtTours->fetch();

        $stmtCompleted = $this->db->prepare($sqlCompleted);
        $stmtCompleted->execute([':guide_id' => $guide_id, ':start' => $startDate, ':end' => $endDate]);
        $completed = $stmtCompleted->fetch();

        $stmtRevenue = $this->db->prepare($sqlRevenue);
        $stmtRevenue->execute([':guide_id' => $guide_id, ':start' => $startDate, ':end' => $endDate]);
        $revenue = $stmtRevenue->fetch();

        return [
            'total_tours' => $tours['total_tours'] ?? 0,
            'completed_tours' => $completed['completed_tours'] ?? 0,
            'total_revenue' => $revenue['total_revenue'] ?? 0,
            'total_guests' => $revenue['total_guests'] ?? 0
        ];
    }

    // 2. Lấy danh sách tour sắp tới (cho bảng ở Dashboard)
    public function getUpcomingTours($guide_id, $limit = 5)
    {
        $sql = "SELECT t.tour_name, s.start_date, s.end_date, s.booked, s.stock
                FROM tour_schedules s
                JOIN tours t ON s.tour_id = t.tour_id
                WHERE s.guide_id = :guide_id AND s.start_date >= CURDATE()
                ORDER BY s.start_date ASC
                LIMIT $limit";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':guide_id' => $guide_id]);
        return $stmt->fetchAll();
    }

    // 3. Lấy dữ liệu biểu đồ doanh thu theo tháng (cho năm hiện tại)
    public function getChartData($guide_id)
    {
        $year = date('Y');
        $sql = "SELECT MONTH(s.start_date) as month, SUM(b.total_price) as revenue
                FROM bookings b
                JOIN tour_schedules s ON b.schedule_id = s.schedule_id
                WHERE s.guide_id = :guide_id 
                AND YEAR(s.start_date) = :year
                AND b.status IN ('Đã xác nhận', 'Hoàn thành')
                GROUP BY MONTH(s.start_date)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':guide_id' => $guide_id, ':year' => $year]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Trả về dạng [Tháng => Tiền]
    }
    // 1. Lấy chuyến đi KẾ TIẾP gần nhất (Quan trọng nhất với HDV)
    public function getNextTour($guide_id) {
        $sql = "SELECT t.tour_name, t.image_url, s.start_date, s.end_date, s.stock, s.booked, s.schedule_id, t.tour_id
                FROM tour_schedules s
                JOIN tours t ON s.tour_id = t.tour_id
                WHERE s.guide_id = :guide_id 
                AND s.start_date >= NOW() 
                ORDER BY s.start_date ASC 
                LIMIT 1"; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':guide_id' => $guide_id]);
        return $stmt->fetch();
    }

    // 2. Lấy đánh giá trung bình (Rating)
    public function getGuideRating($guide_id)
    {
        // Giả sử bảng guide_feedbacks có cột 'rating' (1-5 sao)
        $sql = "SELECT 
                COUNT(*) as total_reviews, 
                AVG(rating) as avg_rating 
            FROM guide_feedbacks 
            WHERE guide_id = :guide_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':guide_id' => $guide_id]);
        return $stmt->fetch();
    }

    // 3. Đếm số ngày công trong tháng (Thay vì doanh thu)
    public function getWorkDaysThisMonth($guide_id)
    {
        // Tính tổng số ngày của các tour đã kết thúc trong tháng này
        $sql = "SELECT SUM(DATEDIFF(end_date, start_date) + 1) as total_days
            FROM tour_schedules 
            WHERE guide_id = :guide_id 
            AND MONTH(start_date) = MONTH(CURRENT_DATE())
            AND YEAR(start_date) = YEAR(CURRENT_DATE())
            AND end_date < NOW()"; // Chỉ tính tour đã xong
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':guide_id' => $guide_id]);
        $result = $stmt->fetch();
        return $result['total_days'] ?? 0;
    }
    // Thêm vào trong class GuideModel



    // 2. Thống kê năng suất (Thay vì doanh thu)
    public function getGuideProductivity($guide_id) {
        // Đếm số tour đã hoàn thành
        $sqlDone = "SELECT COUNT(*) FROM tour_schedules 
                    WHERE guide_id = :gid AND end_date < NOW()";
        
        // Đếm tổng số khách đã phục vụ (Dựa trên số booked của các tour đã qua)
        $sqlGuests = "SELECT SUM(booked) FROM tour_schedules 
                      WHERE guide_id = :gid AND end_date < NOW()";

        // Đếm số tour sắp tới
        $sqlUpcoming = "SELECT COUNT(*) FROM tour_schedules 
                        WHERE guide_id = :gid AND start_date >= NOW()";

        return [
            'tours_done' => $this->db->prepare($sqlDone)->execute([':gid'=>$guide_id]) ? $this->db->prepare($sqlDone)->fetchColumn() : 0,
            'total_guests' => $this->db->prepare($sqlGuests)->execute([':gid'=>$guide_id]) ? $this->db->prepare($sqlGuests)->fetchColumn() : 0,
            'upcoming_count' => $this->db->prepare($sqlUpcoming)->execute([':gid'=>$guide_id]) ? $this->db->prepare($sqlUpcoming)->fetchColumn() : 0,
        ];
    }
    // Thêm vào class GuideModel
public function updateWorkStatus($guide_id, $status) {
    $sql = "UPDATE guides SET work_status = :status WHERE guide_id = :id";
    return $this->db->prepare($sql)->execute([':status' => $status, ':id' => $guide_id]);
}
}
