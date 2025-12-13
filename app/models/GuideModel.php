<?php
class GuideModel extends Model
{
    // Lấy danh sách HDV (JOIN 2 bảng)
    public function getAllGuides($keyword = null)
    {
        $sql = "SELECT u.*, g.* FROM users u 
                JOIN guides g ON u.user_id = g.user_id 
                WHERE u.role = 2";
        
        // [ĐÃ SỬA] Chỉ tìm theo Tên hoặc Số điện thoại (Bỏ email)
        if ($keyword) {
            $sql .= " AND (g.full_name LIKE :kw OR g.phone LIKE :kw)";
        }

        $sql .= " ORDER BY u.user_id DESC";

        if ($keyword) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':kw' => "%$keyword%"]);
            return $stmt->fetchAll();
        } else {
            return $this->db->query($sql)->fetchAll();
        }
    }

    // Lấy chi tiết 1 HDV
    public function getGuideById($user_id)
    {
        $sql = "SELECT u.*, g.experience_years, g.languages, g.guide_id, g.work_status 
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
            $sqlGuide = "INSERT INTO guides (user_id, full_name, date_of_birth, phone, email, experience_years, languages, avatar, work_status) 
                         VALUES (:user_id, :full_name, :birthday, :phone, :email, :exp, :lang, :avatar, 1)";
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

    // [QUAN TRỌNG] HÀM BỊ THIẾU -> NGUYÊN NHÂN LỖI CỦA BẠN
    public function updateWorkStatus($guide_id, $status)
    {
        $sql = "UPDATE guides SET work_status = :status WHERE guide_id = :guide_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':guide_id' => $guide_id]);
    }

    // =================================================================
    // 2. THỐNG KÊ & DASHBOARD
    // =================================================================

    public function getGuideStats($guide_id, $startDate, $endDate)
    {
        $sqlTours = "SELECT COUNT(*) as total_tours FROM tour_schedules WHERE guide_id = :guide_id AND start_date BETWEEN :start AND :end";
        $sqlCompleted = "SELECT COUNT(*) as completed_tours FROM tour_schedules WHERE guide_id = :guide_id AND end_date < NOW() AND start_date BETWEEN :start AND :end";
        $sqlRevenue = "SELECT SUM(b.total_price) as total_revenue, SUM(b.people) as total_guests FROM bookings b JOIN tour_schedules s ON b.schedule_id = s.schedule_id WHERE s.guide_id = :guide_id AND b.status IN ('Đã xác nhận', 'Hoàn thành') AND s.start_date BETWEEN :start AND :end";

        $tours = $this->db->prepare($sqlTours); $tours->execute([':guide_id' => $guide_id, ':start' => $startDate, ':end' => $endDate]);
        $completed = $this->db->prepare($sqlCompleted); $completed->execute([':guide_id' => $guide_id, ':start' => $startDate, ':end' => $endDate]);
        $revenue = $this->db->prepare($sqlRevenue); $revenue->execute([':guide_id' => $guide_id, ':start' => $startDate, ':end' => $endDate]);

        return [
            'total_tours' => $tours->fetchColumn(),
            'completed_tours' => $completed->fetchColumn(),
            'total_revenue' => $revenue->fetch()['total_revenue'] ?? 0,
            'total_guests' => $revenue->fetch()['total_guests'] ?? 0
        ];
    }

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

    public function getChartData($guide_id)
    {
        $year = date('Y');
        $sql = "SELECT MONTH(s.start_date) as month, SUM(b.total_price) as revenue
                FROM bookings b
                JOIN tour_schedules s ON b.schedule_id = s.schedule_id
                WHERE s.guide_id = :guide_id AND YEAR(s.start_date) = :year AND b.status IN ('Đã xác nhận', 'Hoàn thành')
                GROUP BY MONTH(s.start_date)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':guide_id' => $guide_id, ':year' => $year]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function getNextTour($guide_id)
    {
        $sql = "SELECT t.tour_name, t.image_url, s.start_date, s.end_date, s.stock, s.booked, s.schedule_id, t.tour_id
                FROM tour_schedules s
                JOIN tours t ON s.tour_id = t.tour_id
                WHERE s.guide_id = :guide_id AND s.start_date >= NOW() 
                ORDER BY s.start_date ASC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':guide_id' => $guide_id]);
        return $stmt->fetch();
    }
    
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

    public function getGuideProductivity($guide_id)
    {
        $sqlDone = "SELECT COUNT(*) FROM tour_schedules WHERE guide_id = :gid AND end_date < NOW()";
        $sqlGuests = "SELECT SUM(booked) FROM tour_schedules WHERE guide_id = :gid AND end_date < NOW()";
        $sqlUpcoming = "SELECT COUNT(*) FROM tour_schedules WHERE guide_id = :gid AND start_date >= NOW()";

        $done = $this->db->prepare($sqlDone); $done->execute([':gid' => $guide_id]);
        $guests = $this->db->prepare($sqlGuests); $guests->execute([':gid' => $guide_id]);
        $upcoming = $this->db->prepare($sqlUpcoming); $upcoming->execute([':gid' => $guide_id]);

        return [
            'tours_done' => $done->fetchColumn() ?? 0,
            'total_guests' => $guests->fetchColumn() ?? 0,
            'upcoming_count' => $upcoming->fetchColumn() ?? 0,
        ];
    }

    // =================================================================
    // 3. CHECK-IN & NHẬT KÝ
    // =================================================================

    public function hasGuideCheckedIn($guide_id, $schedule_id)
    {
        $sql = "SELECT COUNT(checkin_id) FROM guide_checkins WHERE guide_id = :gid AND schedule_id = :sid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':gid' => $guide_id, ':sid' => $schedule_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function recordCheckin($guide_id, $schedule_id, $note = null)
    {
        $sql = "INSERT INTO guide_checkins (guide_id, schedule_id, checkin_time, note) VALUES (:gid, :sid, NOW(), :note)";
        try {
            return $this->db->prepare($sql)->execute([':gid' => $guide_id, ':sid' => $schedule_id, ':note' => $note]);
        } catch (Exception $e) {
            return false;
        }
    }

public function getCheckinHistory($guide_id, $keyword = null, $date = null, $limit = 20)
{
    $sql = "SELECT 
        ci.*, 
        t.tour_name, 
        s.start_date,
        s.schedule_id,
        (SELECT COUNT(bp.id) FROM booking_passengers bp WHERE bp.schedule_id = ci.schedule_id AND bp.is_present = 1) as present_count,
        (SELECT COUNT(bp.id) FROM booking_passengers bp WHERE bp.schedule_id = ci.schedule_id) as total_passengers
    FROM guide_checkins ci
    JOIN tour_schedules s ON ci.schedule_id = s.schedule_id
    JOIN tours t ON s.tour_id = t.tour_id
    WHERE ci.guide_id = :gid";

    // 1. Logic tìm kiếm (Tên tour hoặc Ghi chú)
    if ($keyword) {
        $sql .= " AND (t.tour_name LIKE :kw OR ci.note LIKE :kw)";
    }

    // 2. Logic tìm kiếm theo Ngày
    if ($date) {
        $sql .= " AND DATE(ci.checkin_time) = :date";
    }

    $sql .= " ORDER BY ci.checkin_time DESC LIMIT :limit";

    $stmt = $this->db->prepare($sql);
    
    // Bind tham số
    $stmt->bindValue(':gid', $guide_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

    if ($keyword) {
        $stmt->bindValue(':kw', "%$keyword%");
    }
    if ($date) {
        $stmt->bindValue(':date', $date);
    }

    $stmt->execute();
    return $stmt->fetchAll();
}
public function getCheckinLogsBySchedule($schedule_id)
    {
        // Kết nối bảng guide_checkins với bảng guides để lấy tên HDV đã check-in
        $sql = "SELECT 
                    gc.*, 
                    g.full_name as guide_name,
                    g.phone as guide_phone
                FROM guide_checkins gc
                LEFT JOIN guides g ON gc.guide_id = g.guide_id
                WHERE gc.schedule_id = :sid
                ORDER BY gc.checkin_time DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':sid' => $schedule_id]);
        return $stmt->fetchAll();
    }
}