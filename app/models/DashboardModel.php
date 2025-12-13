<?php
class DashboardModel extends Model {
    
    // 1. Đếm tổng số Tour đang có
    public function countTotalTours() {
        $sql = "SELECT COUNT(*) as total FROM tours";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

// 1. Hàm lấy thống kê tổng (Thêm tham số $date_custom)
public function getRevenueStats($filter = 'year', $date_custom = null) {
    $sql = "SELECT 
                SUM(total_price) as total_money, 
                SUM(people) as total_people
            FROM bookings 
            WHERE status = 'Hoàn thành'"; 

    if ($filter == 'today') {
        $sql .= " AND DATE(created_at) = CURDATE()";
    } elseif ($filter == 'this_month') {
        $sql .= " AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
    } elseif ($filter == 'year') {
        $sql .= " AND YEAR(created_at) = YEAR(CURRENT_DATE())";
    } elseif ($filter == 'custom' && $date_custom != null) {
        // QUAN TRỌNG: Xử lý ngày custom
        $sql .= " AND DATE(created_at) = '$date_custom'"; 
    }

    $result = $this->db->query($sql)->fetch();
    
    return [
        'total_money' => $result['total_money'] ?? 0,
        'total_people' => $result['total_people'] ?? 0
    ];
}

// 2. Hàm lấy dữ liệu biểu đồ (Thêm tham số $date_custom)
public function getChartData($filter = 'year', $date_custom = null) {
    $sql = "";

    if ($filter == 'today') {
        $sql = "SELECT HOUR(created_at) as label, SUM(total_price) as total 
                FROM bookings 
                WHERE status='Hoàn thành' AND DATE(created_at) = CURDATE()
                GROUP BY HOUR(created_at)";
    } 
    elseif ($filter == 'custom' && $date_custom != null) {
        // QUAN TRỌNG: Xử lý ngày custom (Giống today nhưng thay ngày)
        $sql = "SELECT HOUR(created_at) as label, SUM(total_price) as total 
                FROM bookings 
                WHERE status='Hoàn thành' AND DATE(created_at) = '$date_custom'
                GROUP BY HOUR(created_at)";
    }
    elseif ($filter == 'this_month') {
        $sql = "SELECT DAY(created_at) as label, SUM(total_price) as total 
                FROM bookings 
                WHERE status='Hoàn thành' 
                AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
                AND YEAR(created_at) = YEAR(CURRENT_DATE())
                GROUP BY DAY(created_at)";
    } 
    else { // Mặc định là year
        $sql = "SELECT MONTH(created_at) as label, SUM(total_price) as total 
                FROM bookings 
                WHERE status='Hoàn thành' AND YEAR(created_at) = YEAR(CURRENT_DATE())
                GROUP BY MONTH(created_at)
                ORDER BY label ASC";
    }

    return $this->db->query($sql)->fetchAll();
}
    public function getRecentTransactions($limit = 5) {
        $sql = "SELECT b.id, b.customer_name, b.customer_phone, b.total_price, b.status, b.created_at, t.tour_name
                FROM bookings b
                LEFT JOIN tours t ON b.tour_id = t.tour_id
                ORDER BY b.created_at DESC
                LIMIT $limit";
        return $this->db->query($sql)->fetchAll();
    }
}