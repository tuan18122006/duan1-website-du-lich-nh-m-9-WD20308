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
// File: GuideController.php

public function profile()
{
    // 1. Kiểm tra đăng nhập
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
        header("Location: index.php?act=login");
        exit;
    }

    $user_id = $_SESSION['user']['id'];
    
    // 2. Lấy thông tin
    $guideInfo = $this->guideModel->getGuideById($user_id);

    // Debug: Nếu vẫn lỗi, uncomment 2 dòng dưới để xem ID là gì
    // var_dump($user_id, $guideInfo); die(); 

    if (!$guideInfo) {
        // Fallback: Nếu không lấy được bằng Model Guide, lấy bằng Model User
        $guideInfo = $this->userModel->getOne($user_id);
        if (!$guideInfo) {
            session_destroy(); // Xóa session lỗi
            echo "<script>alert('Tài khoản lỗi! Vui lòng đăng nhập lại.'); window.location.href='index.php?act=login';</script>";
            exit;
        }
        // Gán giá trị mặc định để tránh lỗi View
        $guideInfo['experience_years'] = 0;
        $guideInfo['languages'] = '';
    }

    // 3. Xử lý CẬP NHẬT (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // 3.1 Xử lý ảnh
        $avatar = $guideInfo['avatar'] ?? '';
        if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
            $uploadDir = "assets/uploads/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $safeName = time() . '_' . str_replace(' ', '_', $_FILES['avatar']['name']);
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $safeName)) {
                $avatar = $safeName;
                $_SESSION['user']['avatar'] = $avatar;
            }
        }

        // 3.2 Gom dữ liệu
        $dataUser = [
            'full_name' => $_POST['full_name'],
            'email'     => $_POST['email'],
            'phone'     => $_POST['phone'],
            'birthday'  => $_POST['birthday'],
            'avatar'    => $avatar,
            'password'  => !empty($_POST['password']) ? $_POST['password'] : null
        ];
        
        $dataGuide = [
            'experience_years' => $_POST['experience_years'] ?? 0,
            'languages'        => $_POST['languages'] ?? ''
        ];

        // 3.3 Gọi Model Update
        // Lưu ý: Hàm updateGuide của bạn dùng Transaction, nên nếu bảng guides chưa có dòng nào
        // thì lệnh UPDATE guides ... sẽ không có tác dụng.
        // Tuy nhiên, với logic hiển thị, chỉ cần update users thành công là được.
        
        if ($this->guideModel->updateGuide($user_id, $dataUser, $dataGuide)) {
            $_SESSION['success'] = "Cập nhật thành công!";
            header("Location: index.php?act=guide_profile");
            exit;
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra!";
        }
    }

    // 4. Render View
    $GLOBALS['view_data'] = ['guide' => $guideInfo];
    $view_path = "./app/views/guide/profile.php";
    require_once "./app/views/layouts/guideHeader.php";
}
public function assignedTours()
    {
        // 1. Lấy ID từ URL
        $user_id = $_GET['id'] ?? 0;

        // 2. Lấy thông tin HDV (Chỉ để lấy tên hiển thị tiêu đề)
        $guide = $this->guideModel->getGuideById($user_id);

        if ($guide) {
            // 3. Lấy danh sách tour
            $tours = $this->tourModel->getToursByGuide($guide['guide_id']);

            // 4. Truyền dữ liệu
            $GLOBALS['view_data'] = [
                'guide' => $guide,
                'tours' => $tours
            ];

            // Trỏ đến file view mới (chỉ có bảng tour)
            $view_path = "app/views/operations/assigned_tours.php"; 
            
            require_once "./app/views/layouts/main.php";
        } else {
            $_SESSION['error'] = "Không tìm thấy HDV!";
            header('Location: index.php?act=hr_management');
        }
    }
}