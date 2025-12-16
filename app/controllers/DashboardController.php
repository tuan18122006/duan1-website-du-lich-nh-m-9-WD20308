<?php
class DashboardController extends Controller
{
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = $this->model('DashboardModel');
    }

    // --- FUNCTION 1: DASHBOARD ADMIN ---
    public function showDashboardCategory() {
        // 1. KIỂM TRA QUYỀN VÀ LOGIN
        Controller::requireAdmin();
        if (empty($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        // 2. LẤY THAM SỐ TỪ URL
        $filter = $_GET['time'] ?? 'year'; 
        $date_custom = $_GET['date'] ?? date('Y-m-d');

        // 3. GỌI MODEL LẤY SỐ LIỆU
        // - Thống kê doanh thu & khách
        $stats = $this->dashboardModel->getRevenueStats($filter, $date_custom);
        // - Dữ liệu biểu đồ
        $rawData = $this->dashboardModel->getChartData($filter, $date_custom);
        // - [MỚI] Lấy 10 giao dịch gần nhất
        $recent_transactions = $this->dashboardModel->getRecentTransactions(10);
        
        // 4. XỬ LÝ DỮ LIỆU BIỂU ĐỒ
        $chart_labels = [];
        $chart_data = []; 

        if ($filter == 'year') {
            // Theo năm (12 tháng)
            for ($m = 1; $m <= 12; $m++) {
                $chart_labels[] = "Tháng $m";
                $chart_data[$m] = 0;
            }
            foreach ($rawData as $item) {
                $chart_data[intval($item['label'])] = (int)$item['total'];
            }

        } elseif ($filter == 'this_month') {
            // Theo tháng này (Số ngày trong tháng)
            $daysInMonth = date('t'); 
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $chart_labels[] = "$d/" . date('m');
                $chart_data[$d] = 0;
            }
            foreach ($rawData as $item) {
                $chart_data[intval($item['label'])] = (int)$item['total'];
            }

        } else { 
            // Theo ngày (24 giờ)
            for ($h = 0; $h <= 23; $h++) {
                $chart_labels[] = "$h:00";
                $chart_data[$h] = 0;
            }
            foreach ($rawData as $item) {
                $chart_data[intval($item['label'])] = (int)$item['total'];
            }
        }

        // Reset key mảng về 0,1,2... để JS đọc được
        $chart_data = array_values($chart_data);

        // 5. ĐÓNG GÓI DỮ LIỆU & HIỂN THỊ VIEW
        $data = [
                'total_bookings'      => $stats['total_bookings'],
                'total_revenue'       => $stats['total_money'], 
                'total_customers'     => $stats['total_people'],
                'chart_data_json'     => json_encode($chart_data),
                'chart_labels'        => $chart_labels,         
                'currentFilter'       => $filter,
                'date_custom'         => $date_custom,
                'recent_transactions' => $recent_transactions 
            ];

            extract($data); 

            $view_path = "./app/views/dashboard/dashboard.php";
            require_once "./app/views/layouts/main.php";
        }

    // --- FUNCTION 2: DASHBOARD GUIDE ---
    public function guideHome()
    {
        Controller::requireGuide();

        if (empty($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $view_path = "./app/views/dashboard/dashbroad_guide.php";
        require_once "./app/views/layouts/mainGuide.php";
    }
}
?>