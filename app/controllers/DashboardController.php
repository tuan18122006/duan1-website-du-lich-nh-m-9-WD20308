<?php
class DashboardController extends Controller
{
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = $this->model('DashboardModel');
    }

// Trong DashboardController.php

public function showDashboardCategory() {
    // 1. Lấy tham số filter và ngày tùy chọn từ URL
    $filter = $_GET['time'] ?? 'year'; 
    $date_custom = $_GET['date'] ?? date('Y-m-d'); // Lấy ngày từ URL, nếu không có thì lấy hôm nay

    // 2. Lấy số liệu tổng (TRUYỀN THÊM $date_custom VÀO ĐÂY)
    $stats = $this->dashboardModel->getRevenueStats($filter, $date_custom); 
    $countTours = $this->dashboardModel->countTotalTours();
    
    // 3. Lấy dữ liệu biểu đồ (TRUYỀN THÊM $date_custom VÀO ĐÂY)
    $rawData = $this->dashboardModel->getChartData($filter, $date_custom);
    
    // 4. XỬ LÝ DỮ LIỆU BIỂU ĐỒ
    $chart_labels = [];
    $chart_data = [];

    // --- LOGIC VẼ KHUNG BIỂU ĐỒ ---
    if ($filter == 'year') {
        // ... (Giữ nguyên logic năm) ...
        for ($m = 1; $m <= 12; $m++) {
            $chart_labels[] = "Tháng $m";
            $chart_data[$m] = 0;
        }
        foreach ($rawData as $item) {
            $month = intval($item['label']); 
            if (isset($chart_data[$month])) $chart_data[$month] = (int)$item['total'];
        }
        $chart_data = array_values($chart_data);

    } elseif ($filter == 'this_month') {
        // ... (Giữ nguyên logic tháng) ...
        $daysInMonth = date('t');
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $chart_labels[] = "$d/" . date('m');
            $chart_data[$d] = 0;
        }
        foreach ($rawData as $item) {
            $day = intval($item['label']);
            if (isset($chart_data[$day])) $chart_data[$day] = (int)$item['total'];
        }
        $chart_data = array_values($chart_data);

    } else { 
        // TRƯỜNG HỢP: 'today' HOẶC 'custom' (Vẽ biểu đồ theo giờ 0-23h)
        for ($h = 0; $h <= 23; $h++) {
            $chart_labels[] = "$h:00";
            $chart_data[$h] = 0;
        }
        foreach ($rawData as $item) {
            $hour = intval($item['label']);
            if (isset($chart_data[$hour])) $chart_data[$hour] = (int)$item['total'];
        }
        $chart_data = array_values($chart_data);
    }

    // 5. Đóng gói dữ liệu
    $data = [
        'total_tours'     => $countTours,           
        'total_revenue'   => $stats['total_money'], 
        'total_customers' => $stats['total_people'],
        'chart_data_json' => json_encode($chart_data),
        'chart_labels'    => $chart_labels,         
        'currentFilter'   => $filter
    ];

    extract($data); 

    // Gọi View (Chắc chắn file view của bạn tên là dashboard.php)
    $view_path = "./app/views/dashboard/dashboard.php"; 
    require_once "./app/views/layouts/main.php";
}
}