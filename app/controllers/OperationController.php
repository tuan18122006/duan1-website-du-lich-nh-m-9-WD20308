<?php
class OperationController extends Controller {
    private $tourModel;
    private $guideModel;
    private $bookingModel;

    public function __construct() {
        $this->tourModel = $this->model('TourModel');
        $this->guideModel = $this->model('GuideModel');
        $this->bookingModel = $this->model('BookingModel');
    }

    // 1. QUẢN LÝ NHÂN SỰ (HR)
    public function hrManagement() {
        // 1. Xử lý cập nhật trạng thái (Giữ nguyên code cũ)
        if(isset($_POST['update_status'])) {
            $this->guideModel->updateWorkStatus($_POST['guide_id'], $_POST['status']);
            header("Location: index.php?act=hr_management"); 
            exit;
        }

        // 2. [THÊM MỚI] Lấy từ khóa tìm kiếm từ URL
        $keyword = $_GET['keyword'] ?? null;

        // 3. Truyền từ khóa vào Model để lọc dữ liệu
        $guides = $this->guideModel->getAllGuides($keyword);

        $view_path = './app/views/operations/hr_list.php';
        $page_title = "Quản lý Nhân sự";
        require_once './app/views/layouts/main.php';
    }

public function departureManagement() {
        // Cập nhật điểm tập kết (Giữ nguyên)
        if(isset($_POST['update_point'])) {
            $this->tourModel->updateMeetingPoint($_POST['schedule_id'], $_POST['meeting_point']);
            header("Location: index.php?act=departure_management"); exit;
        }
        $keyword = $_GET['keyword'] ?? null;
        $date = $_GET['date'] ?? null;
        // Lấy danh sách lịch trình
        $schedules = $this->tourModel->getAllSchedulesWithDetails($keyword, $date);

        // [THÊM] Logic tính toán check-in (Chuyển từ hàm checkinOverview sang đây)
        foreach($schedules as &$s) {
            $stats = $this->bookingModel->getCheckInStats($s['schedule_id']);
            $s['checkin_count'] = $stats['checked'];
            $s['total_guests'] = $stats['total'];
            
            // Tính trạng thái Tour
            $now = time();
            $start = strtotime($s['start_date']);
            $end = strtotime($s['end_date']);
            
            if ($now < $start) $s['tour_state'] = 'Chờ khởi hành';
            elseif ($now >= $start && $now <= $end) $s['tour_state'] = 'Đang khởi hành';
            else $s['tour_state'] = 'Kết thúc';
        }

        $view_path = './app/views/operations/departure_list.php';
        $page_title = "Quản lý Khởi hành & Check-in"; // Đổi tên tiêu đề
        require_once './app/views/layouts/main.php';
    }

    // 3. CHI TIẾT ĐOÀN (Giữ nguyên để xem chi tiết)
    public function detailGroup() {
        Controller::requireAdmin();
        $schedule_id = $_GET['id'] ?? 0;
        
        $schedule = $this->tourModel->getScheduleById($schedule_id);
        if (!$schedule) {
            $_SESSION['error'] = "Không tìm thấy lịch trình!";
            header("Location: index.php?act=departure_management"); // Redirect về trang mới
            exit;
        }

        $passengers = $this->bookingModel->getPassengersBySchedule($schedule_id);
        $logs = $this->guideModel->getCheckinLogsBySchedule($schedule_id);

        $data = ['schedule' => $schedule, 'passengers' => $passengers, 'logs' => $logs];
        extract($data);

        $view_path = './app/views/operations/tour_detail_checkin.php';
        require_once './app/views/layouts/main.php';
    }
}