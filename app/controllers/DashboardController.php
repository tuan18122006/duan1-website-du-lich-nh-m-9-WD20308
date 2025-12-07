<?php
class DashboardController extends Controller
{
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = $this->model('DashboardModel');
    }

    // --- FUNCTION 1: DASHBOARD ADMIN ---
    public function showDashboardCategory() {
        // 1. KIỂM TRA QUYỀN VÀ LOGIN TRƯỚC (Đưa lên đầu)
        Controller::requireAdmin();
        if (empty($_SESSION['user'])) {
            header("Location: index.php?act=login");
            exit;
        }

        // 2. LẤY THAM SỐ TỪ URL
        $filter = $_GET['time'] ?? 'year'; 
        $date_custom = $_GET['date'] ?? date('Y-m-d');

        // 3. GỌI MODEL LẤY SỐ LIỆU
        $stats = $this->dashboardModel->getRevenueStats($filter, $date_custom); 
        $countTours = $this->dashboardModel->countTotalTours();
        
        // Lấy dữ liệu thô cho biểu đồ
        $rawData = $this->dashboardModel->getChartData($filter, $date_custom);
        
        // 4. XỬ LÝ DỮ LIỆU BIỂU ĐỒ
        $chart_labels = [];
        $chart_data = []; // Mảng tạm có key để map dữ liệu

        // --- LOGIC VẼ KHUNG BIỂU ĐỒ ---
        if ($filter == 'year') {
            // [TRƯỜNG HỢP 1]: Theo năm (12 tháng)
            for ($m = 1; $m <= 12; $m++) {
                $chart_labels[] = "Tháng $m";
                $chart_data[$m] = 0; // Khởi tạo bằng 0
            }
            // Đổ dữ liệu từ DB vào
            foreach ($rawData as $item) {
                $month = intval($item['label']); 
                if (isset($chart_data[$month])) {
                    $chart_data[$month] = (int)$item['total'];
                }
            }

        } elseif ($filter == 'this_month') {
            // [TRƯỜNG HỢP 2]: Theo tháng này (Số ngày trong tháng)
            $daysInMonth = date('t'); // Lấy số ngày của tháng hiện tại
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $chart_labels[] = "$d/" . date('m');
                $chart_data[$d] = 0;
            }
            // Đổ dữ liệu
            foreach ($rawData as $item) {
                $day = intval($item['label']);
                if (isset($chart_data[$day])) {
                    $chart_data[$day] = (int)$item['total'];
                }
            }

        } else { 
            // [TRƯỜNG HỢP 3]: Theo ngày ('today' hoặc 'custom') - 24 giờ
            for ($h = 0; $h <= 23; $h++) {
                $chart_labels[] = "$h:00";
                $chart_data[$h] = 0;
            }
            // Đổ dữ liệu
            foreach ($rawData as $item) {
                $hour = intval($item['label']);
                if (isset($chart_data[$hour])) {
                    $chart_data[$hour] = (int)$item['total'];
                }
            }
        }

        // Reset key của mảng data về 0, 1, 2... để JS đọc được
        $chart_data = array_values($chart_data);

        // 5. ĐÓNG GÓI DỮ LIỆU & HIỂN THỊ VIEW (Đưa ra ngoài IF/ELSE)
        $data = [
            'total_tours'     => $countTours,           
            'total_revenue'   => $stats['total_money'], 
            'total_customers' => $stats['total_people'],
            'chart_data_json' => json_encode($chart_data),
            'chart_labels'    => $chart_labels,         
            'currentFilter'   => $filter,
            'date_custom'     => $date_custom // Truyền lại để view hiển thị ngày đang chọn
        ];

        extract($data); 

        // Đường dẫn view
        $view_path = "./app/views/dashboard/dashboard.php";

        // Load layout chính    
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

        // Đường dẫn view
        $view_path = "./app/views/dashboard/dashbroad_guide.php";

        // Load layout chính
        require_once "./app/views/layouts/mainGuide.php";
    }
}
?>