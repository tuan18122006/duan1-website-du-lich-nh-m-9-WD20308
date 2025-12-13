<?php
class GuideController extends Controller
{
    public $guideModel;
    public $userModel;
    public $tourModel;
    public $bookingModel;

    public function __construct()
    {
        // Khởi tạo các Model cần thiết
        $this->guideModel = $this->model('GuideModel');
        $this->userModel = $this->model('UserModel');
        $this->tourModel = $this->model('TourModel');
        $this->bookingModel = $this->model('BookingModel');
    }

    // --- CÁC HÀM QUẢN LÝ GUIDE (Dành cho Admin) ---
    public function index() {
        $listGuides = $this->guideModel->getAllGuides();
        $page_css = "assets/css/user.css";
        $view_path = "app/views/guides/list.php";
        require_once "./app/views/layouts/main.php";
    }

    public function create() {
        $page_css = "assets/css/user.css";
        $view_path = "app/views/guides/add.php";
        require_once "./app/views/layouts/main.php";
    }

    public function store() {
        if (isset($_POST['add_guide'])) {
            if ($this->userModel->checkUsernameExists($_POST['username'])) {
                $_SESSION['error'] = "Tên đăng nhập đã tồn tại!";
                echo "<script>window.history.back();</script>";
                return;
            }
            // Xử lý upload ảnh và lưu dữ liệu (giữ nguyên logic cũ của bạn)
            $avatar = "";
            if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
                $safeName = str_replace(' ', '_', $_FILES['avatar']['name']);
                $avatar = time() . '_' . $safeName;
                move_uploaded_file($_FILES['avatar']['tmp_name'], "assets/uploads/" . $avatar);
            }
            $dataUser = [
                ':username' => $_POST['username'], ':password' => $_POST['password'],
                ':full_name' => $_POST['full_name'], ':email' => $_POST['email'],
                ':phone' => $_POST['phone'], ':birthday' => $_POST['birthday'], ':avatar' => $avatar
            ];
            $dataGuide = ['experience_years' => $_POST['experience_years'], 'languages' => $_POST['languages']];

            if ($this->guideModel->createGuide($dataUser, $dataGuide)) {
                $_SESSION['success'] = "Thêm HDV thành công!";
                header('Location: index.php?act=list_guide');
            } else {
                $_SESSION['error'] = "Lỗi hệ thống!";
                header('Location: index.php?act=list_guide');
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $guide = $this->guideModel->getGuideById($id);
        if ($guide) {
            $page_css = "assets/css/user.css";
            $view_path = "app/views/guides/edit.php";
            require_once "./app/views/layouts/main.php";
        } else {
            $_SESSION['error'] = "Không tìm thấy HDV!";
            header('Location: index.php?act=list_guide');
        }
    }

    public function update() {
        if (isset($_POST['update_guide'])) {
            $id = $_POST['user_id'];
            $avatar = $_POST['old_avatar'];
            if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
                $safeName = str_replace(' ', '_', $_FILES['avatar']['name']);
                $avatar = time() . '_' . $safeName;
                move_uploaded_file($_FILES['avatar']['tmp_name'], "assets/uploads/" . $avatar);
            }
            $dataUser = [
                'full_name' => $_POST['full_name'], 'email' => $_POST['email'],
                'phone' => $_POST['phone'], 'birthday' => $_POST['birthday'],
                'password' => $_POST['password'], 'avatar' => $avatar
            ];
            $dataGuide = ['experience_years' => $_POST['experience_years'], 'languages' => $_POST['languages']];

            if ($this->guideModel->updateGuide($id, $dataUser, $dataGuide)) {
                $_SESSION['success'] = "Cập nhật thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }
            header('Location: index.php?act=list_guide');
        }
    }

    public function detail() {
        $id = $_GET['id'] ?? 0;
        $guide = $this->guideModel->getGuideById($id);
        $tours = $this->tourModel->getToursByGuide($id);
        if ($guide) {
            $page_css = "assets/css/user.css";
            $view_path = "app/views/guides/detail.php";
            require_once "./app/views/layouts/main.php";
        } else {
            header('Location: index.php?act=list_guide');
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = "Đã xóa HDV!";
        } else {
            $_SESSION['error'] = "Lỗi xóa dữ liệu!";
        }
        header('Location: index.php?act=list_guide');
    }


    // ==========================================
    // PHẦN CHỨC NĂNG RIÊNG CHO HDV (ROLE 2)
    // ==========================================

    public function dashboard()
    {
        // Kiểm tra quyền
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header("Location: index.php");
            exit;
        }

        // Lấy Guide ID
        $guide_id = $_SESSION['user']['guide_id'] ?? null;
        if (!$guide_id) {
            $guide = $this->guideModel->getGuideById($_SESSION['user']['user_id']);
            $guide_id = $guide['guide_id'];
            $_SESSION['user']['guide_id'] = $guide_id;
        }

        // Lấy số liệu
        $next_tour = $this->guideModel->getNextTour($guide_id);
        $stats = $this->guideModel->getGuideProductivity($guide_id);
        $upcoming_tours = $this->guideModel->getUpcomingTours($guide_id, 5);

        // Truyền dữ liệu sang View
        $GLOBALS['view_data'] = [
            'next_tour' => $next_tour,
            'stats' => $stats,
            'upcoming_tours' => $upcoming_tours
        ];

        $view_path = "./app/views/dashboard/dashboard_guide.php";
        require_once "./app/views/layouts/guideHeader.php";
    }

    public function myTour()
{
    // Lấy Guide ID (Giữ nguyên logic cũ)
    $current_guide_id = $_SESSION['user']['guide_id'] ?? 0;
    if (!$current_guide_id) {
        $guide = $this->guideModel->getGuideById($_SESSION['user']['user_id']);
        $current_guide_id = $guide['guide_id'] ?? 0;
        $_SESSION['user']['guide_id'] = $current_guide_id;
    }

    // [CẬP NHẬT] Lấy cả Keyword và Date
    $keyword = $_GET['keyword'] ?? null;
    $date    = $_GET['date'] ?? null;

    // Truyền đủ 3 tham số vào Model
    $tours = $this->tourModel->getToursByGuide($current_guide_id, $keyword, $date);

    // Lấy danh sách hành khách (Logic cũ giữ nguyên)
    $passengers_by_schedule = [];
    foreach ($tours as $tour) {
        $schedule_id = $tour['schedule_id'] ?? 0;
        if ($schedule_id) {
            $passengers_by_schedule[$schedule_id] = $this->bookingModel->getPassengersBySchedule($schedule_id);
        }
    }

    // Truyền lại biến date sang View để giữ giá trị trong ô input
    $GLOBALS['view_data'] = [
        'tours' => $tours,
        'passengers_by_schedule' => $passengers_by_schedule,
        'keyword' => $keyword,
        'date' => $date 
    ];

    $view_path = "./app/views/guide/myTour.php";
    require_once "./app/views/layouts/guideHeader.php";
}
    // XỬ LÝ CHECK-IN & GHI NHẬT KÝ
    public function checkin()
    {
        if (!isset($_POST['schedule_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Yêu cầu không hợp lệ.";
            header("Location: index.php?act=my_tour");
            exit;
        }

        $schedule_id = $_POST['schedule_id'];
        $note = $_POST['note'] ?? '';
        $guide_id = $_SESSION['user']['guide_id'] ?? 0;
        
        // Mảng ID khách hàng ĐƯỢC CHỌN (có mặt)
        $passenger_ids = $_POST['passenger_ids'] ?? []; 

        // 1. Ghi nhật ký vào bảng guide_checkins
        $is_logged = $this->guideModel->recordCheckin($guide_id, $schedule_id, $note);

        // 2. Cập nhật trạng thái điểm danh trong bảng booking_passengers
        $is_attendance_updated = $this->bookingModel->updateCheckinStatus($schedule_id, $passenger_ids);

        if ($is_logged) {
            $_SESSION['success'] = "Đã lưu nhật ký và điểm danh thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi khi lưu nhật ký!";
        }

        header("Location: index.php?act=my_tour");
        exit;
    }

    public function passengerList()
    {
        $schedule_id = $_GET['schedule_id'] ?? 0;
        
        // Lấy thông tin
        $schedule_info = $this->tourModel->getScheduleById($schedule_id);
        $tour_info = ($schedule_info) ? $this->tourModel->getTourById($schedule_info['tour_id']) : null;
        $passengers = $this->bookingModel->getPassengersBySchedule($schedule_id);

        $GLOBALS['view_data'] = [
            'passengers' => $passengers,
            'schedule' => $schedule_info,
            'tour' => $tour_info,
        ];

        $view_path = './app/views/guide/passenger_list.php';
        require_once "./app/views/layouts/guideHeader.php";
    }
// File: app/controllers/GuideController.php

public function checkinHistory()
{
    // 1. Kiểm tra quyền & Lấy ID (Giữ nguyên logic cũ của bạn)
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
        header("Location: index.php");
        exit;
    }
    $guide_id = $_SESSION['user']['guide_id'] ?? null;
    if (!$guide_id) {
        $guide = $this->guideModel->getGuideById($_SESSION['user']['user_id']);
        $guide_id = $guide['guide_id'] ?? 0;
        $_SESSION['user']['guide_id'] = $guide_id;
    }

    // 2. [SỬA ĐOẠN NÀY] Lấy tham số tìm kiếm giống trang My Tour
    $keyword = $_GET['keyword'] ?? null;
    $date    = $_GET['date'] ?? null;

    // 3. Gọi Model với tham số mới
    $history = $this->guideModel->getCheckinHistory($guide_id, $keyword, $date, 20);

    // 4. Truyền dữ liệu sang View
    $GLOBALS['view_data'] = [
        'history' => $history,
        'keyword' => $keyword, // Để giữ lại giá trị trong ô input
        'date'    => $date
    ];

    $view_path = "./app/views/guide/checkinHistory.php";
    require_once "./app/views/layouts/guideHeader.php";
}
}