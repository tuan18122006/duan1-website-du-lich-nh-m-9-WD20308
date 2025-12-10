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
        // Xử lý cập nhật trạng thái
        if(isset($_POST['update_status'])) {
            $this->guideModel->updateWorkStatus($_POST['guide_id'], $_POST['status']);
            header("Location: index.php?act=hr_management"); exit;
        }

        $guides = $this->guideModel->getAllGuides();
        $view_path = './app/views/operations/hr_list.php';
        $page_title = "Quản lý Nhân sự";
        require_once './app/views/layouts/main.php';
    }

    // 2. QUẢN LÝ KHỞI HÀNH & PHÂN BỔ
    public function departureManagement() {
        // Cập nhật điểm tập kết
        if(isset($_POST['update_point'])) {
            $this->tourModel->updateMeetingPoint($_POST['schedule_id'], $_POST['meeting_point']);
            header("Location: index.php?act=departure_management"); exit;
        }

        $schedules = $this->tourModel->getAllSchedulesWithDetails();
        $view_path = './app/views/operations/departure_list.php';
        $page_title = "Quản lý Khởi hành";
        require_once './app/views/layouts/main.php';
    }

    // 3. QUẢN LÝ ĐOÀN KHÁCH & CHECK-IN (ADMIN VIEW)
    public function checkinOverview() {
        $schedules = $this->tourModel->getAllSchedulesWithDetails();
        
        // Gắn thêm thông tin check-in vào mảng schedules
        foreach($schedules as &$s) {
            $stats = $this->bookingModel->getCheckInStats($s['schedule_id']);
            $s['checkin_count'] = $stats['checked'];
            $s['total_guests'] = $stats['total'];
            
            // Tính trạng thái Tour theo thời gian
            $now = time();
            $start = strtotime($s['start_date']);
            $end = strtotime($s['end_date']);
            
            if ($now < $start) $s['tour_state'] = 'Chờ khởi hành';
            elseif ($now >= $start && $now <= $end) $s['tour_state'] = 'Đang khởi hành';
            else $s['tour_state'] = 'Kết thúc';
        }

        $view_path = './app/views/operations/checkin_overview.php';
        $page_title = "Quản lý Check-in Đoàn";
        require_once './app/views/layouts/main.php';
    }
}