<?php
class GuideController extends Controller
{
    public $guideModel;
    public $userModel;
    public $tourModel;
    public $bookingModel;

    public function __construct()
    {
        $this->guideModel = $this->model('GuideModel');
        $this->userModel = $this->model('UserModel');
        $this->tourModel = $this->model('TourModel');
        $this->bookingModel = $this->model('BookingModel');
    }

    // 1. LIST (Admin xem danh sách HDV)
    public function index()
    {
        $listGuides = $this->guideModel->getAllGuides();
        $page_css = "assets/css/user.css";
        $view_path = "app/views/guides/list.php";
        require_once "./app/views/layouts/main.php";
    }

    // 2. FORM ADD
    public function create()
    {
        $page_css = "assets/css/user.css";
        $view_path = "app/views/guides/add.php";
        require_once "./app/views/layouts/main.php";
    }

    // 3. STORE
    public function store()
    {
        if (isset($_POST['add_guide'])) {
            // Check trùng username bên bảng users
            if ($this->userModel->checkUsernameExists($_POST['username'])) {
                $_SESSION['error'] = "Tên đăng nhập đã tồn tại!";
                echo "<script>window.history.back();</script>";
                return;
            }

            $avatar = "";
            if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
                $safeName = str_replace(' ', '_', $_FILES['avatar']['name']);
                $avatar = time() . '_' . $safeName;
                move_uploaded_file($_FILES['avatar']['tmp_name'], "assets/uploads/" . $avatar);
            }

            $dataUser = [
                ':username' => $_POST['username'],
                ':password' => $_POST['password'],
                ':full_name' => $_POST['full_name'],
                ':email' => $_POST['email'],
                ':phone' => $_POST['phone'],
                ':birthday' => $_POST['birthday'],
                ':avatar' => $avatar
            ];

            $dataGuide = [
                'experience_years' => $_POST['experience_years'],
                'languages' => $_POST['languages']
            ];

            if ($this->guideModel->createGuide($dataUser, $dataGuide)) {
                $_SESSION['success'] = "Thêm HDV thành công!";
                header('Location: index.php?act=list_guide');
            } else {
                $_SESSION['error'] = "Lỗi hệ thống!";
                header('Location: index.php?act=list_guide');
            }
        }
    }

    // 4. FORM EDIT
    public function edit()
    {
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

    // 5. UPDATE
    public function update()
    {
        if (isset($_POST['update_guide'])) {
            $id = $_POST['user_id'];

            $avatar = $_POST['old_avatar'];
            if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
                $safeName = str_replace(' ', '_', $_FILES['avatar']['name']);
                $avatar = time() . '_' . $safeName;
                move_uploaded_file($_FILES['avatar']['tmp_name'], "assets/uploads/" . $avatar);
            }

            $dataUser = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'birthday' => $_POST['birthday'],
                'password' => $_POST['password'],
                'avatar' => $avatar
            ];

            $dataGuide = [
                'experience_years' => $_POST['experience_years'],
                'languages' => $_POST['languages']
            ];

            if ($this->guideModel->updateGuide($id, $dataUser, $dataGuide)) {
                $_SESSION['success'] = "Cập nhật thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }
            header('Location: index.php?act=list_guide');
        }
    }

    // 6. DETAIL
    public function detail()
    {
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

    // 7. DELETE
    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = "Đã xóa HDV và tài khoản liên quan!";
        } else {
            $_SESSION['error'] = "Lỗi xóa dữ liệu!";
        }
        header('Location: index.php?act=list_guide');
    }

    // ==========================================
    // PHẦN CHỨC NĂNG DÀNH RIÊNG CHO HDV (ROLE 2)
    // ==========================================

    public function dashboard()
    {
        // 1. Kiểm tra quyền & Lấy ID
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header("Location: index.php");
            exit;
        }

        $guide_id = $_SESSION['user']['guide_id'] ?? null;
        if (!$guide_id) {
            $guide = $this->guideModel->getGuideById($_SESSION['user']['user_id']);
            $guide_id = $guide['guide_id'];
            $_SESSION['user']['guide_id'] = $guide_id;
        }

        // 2. Lấy dữ liệu (Giữ nguyên code cũ của bạn)
        $next_tour = $this->guideModel->getNextTour($guide_id);
        $stats = $this->guideModel->getGuideProductivity($guide_id);
        $upcoming_tours = $this->guideModel->getUpcomingTours($guide_id, 5);

        // 3. CẤU HÌNH VIEW (SỬA ĐOẠN NÀY)
        // Dùng đường dẫn chuẩn sau khi đã đổi tên ở Bước 1
        $path = "./app/views/dashboard/dashboard_guide.php";

        // Kiểm tra file tồn tại chưa
        if (!file_exists($path)) {
            die("LỖI: Không tìm thấy file tại <strong>$path</strong>. <br>Hãy chắc chắn bạn đã đổi tên file thành <strong>dashboard_guide.php</strong> và để trong thư mục <strong>app/views/guides/</strong>");
        }

        // Dùng GLOBALS để truyền biến chắc chắn 100%
        $GLOBALS['view_path'] = $path;

        require_once "./app/views/layouts/guideHeader.php";
    }

    public function myTour()
    {
        // 1. Lấy và định nghĩa các biến cần thiết
        $guide_model = $this->guideModel;

        $current_guide_id = $_SESSION['user']['guide_id'] ?? 0;
        if (!$current_guide_id) {
            $guide = $guide_model->getGuideById($_SESSION['user']['user_id']);
            $current_guide_id = $guide['guide_id'] ?? 0;
            $_SESSION['user']['guide_id'] = $current_guide_id;
        }

        $tours = $this->tourModel->getToursByGuide($current_guide_id);

        // --- BỔ SUNG LOGIC LẤY DANH SÁCH HÀNH KHÁCH CHO TỪNG TOUR ---
        $passengers_by_schedule = [];
        foreach ($tours as $tour) {
            $schedule_id = $tour['schedule_id'] ?? 0;
            if ($schedule_id) {
                // Lấy danh sách hành khách cho từng lịch trình
                $passengers_by_schedule[$schedule_id] = $this->bookingModel->getPassengersBySchedule($schedule_id);
            }
        }

        $data_for_view = [
            'guide_model' => $guide_model,
            'booking_model' => $this->bookingModel,
            'current_guide_id' => $current_guide_id,
            'tours' => $tours,
            'passengers_by_schedule' => $passengers_by_schedule,
        ];

        // Truyền mảng dữ liệu vào biến toàn cục.
        $GLOBALS['view_data'] = $data_for_view;

        $view_path = "./app/views/guide/myTour.php";

        require_once "./app/views/layouts/guideHeader.php";
    }

    public function passengerList()
    {
        // Lấy schedule_id từ URL
        $schedule_id = $_GET['schedule_id'] ?? 0;

        // Kiểm tra guide_id (từ session) và schedule_id
        $guide_id = $_SESSION['user']['guide_id'] ?? 0;

        if ($guide_id == 0 || $schedule_id == 0) {
            $_SESSION['error'] = "Thiếu ID hướng dẫn viên hoặc ID lịch trình tour.";
            header("Location: index.php?act=guide/myTour");
            exit;
        }

        // --- FIX: Lấy thông tin chi tiết Lịch trình (Schedule) và Tour ---
        // Sử dụng hàm getScheduleById() đã có trong TourModel
        $schedule_info = $this->tourModel->getScheduleById($schedule_id);
        $tour_info = null;
        if ($schedule_info && $schedule_info['tour_id']) {
            $tour_info = $this->tourModel->getTourById($schedule_info['tour_id']);
        }
        // ------------------------------------------------------------------

        // Nếu pass kiểm tra, chạy logic lấy dữ liệu
        // Dùng $this->bookingModel đã khởi tạo
        $passengers = $this->bookingModel->getPassengersBySchedule($schedule_id);

        // Chuẩn bị dữ liệu truyền vào View
        $data_for_view = [
            'passengers' => $passengers,
            'schedule' => $schedule_info, // Thông tin lịch trình
            'tour' => $tour_info,         // Thông tin tour
        ];

        $GLOBALS['view_data'] = $data_for_view;

        // Load View danh sách hành khách
        $view_path = './app/views/guide/passenger_list.php'; // Hoặc passenger_list.php tùy tên file bạn dùng
        require_once "./app/views/layouts/guideHeader.php";
    }

    public function checkin()
    {
        // 1. Kiểm tra quyền và method
        if (!isset($_POST['schedule_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Yêu cầu không hợp lệ.";
            header("Location: index.php?act=my_tour");
            exit;
        }

        // 2. Lấy dữ liệu
        $schedule_id = $_POST['schedule_id'];
        $note = $_POST['note'] ?? '';
        $guide_id = $_SESSION['user']['guide_id'] ?? null;

        // Cần đảm bảo guide_id được lấy chính xác nếu chưa có trong session
        if (!$guide_id && isset($_SESSION['user']['user_id'])) {
            $guide = $this->guideModel->getGuideById($_SESSION['user']['user_id']);
            $guide_id = $guide['guide_id'] ?? 0;
        }


        // 3. Kiểm tra Hướng dẫn viên và Tour hợp lệ
        if ($guide_id == 0 || $schedule_id == 0) {
            $_SESSION['error'] = "Thiếu ID hướng dẫn viên hoặc ID lịch trình tour.";
            header("Location: index.php?act=my_tour");
            exit;
        }

        $checked_passenger_ids = $_POST['passenger_ids'] ?? []; // Mảng chứa ID khách có mặt

        // 4. Ghi nhận Nhật ký mới (BƯỚC NÀY KHÔNG ĐỔI)
        if ($this->guideModel->recordCheckin($guide_id, $schedule_id, $note)) {

            // --- XỬ LÝ ĐIỂM DANH ---
            // Dùng $this->bookingModel đã khởi tạo
            // 4a. Cập nhật trạng thái điểm danh
            $this->bookingModel->updateCheckinStatus($schedule_id, $checked_passenger_ids);
            // -------------------------

            $_SESSION['success'] = "Ghi nhật ký và điểm danh thành công!";
        } else {
            $_SESSION['error'] = "Lỗi hệ thống hoặc lịch trình đã được ghi nhật ký.";
        }

        header("Location: index.php?act=my_tour");
        exit;
    }

    public function checkinHistory()
    {
        // 1. Kiểm tra quyền HDV
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

        $history = $this->guideModel->getCheckinHistory($guide_id, 20);

        // CẤU HÌNH VIEW ĐỒNG BỘ
        $data_for_view = [
            'history' => $history,
        ];

        $GLOBALS['view_data'] = $data_for_view;

        $view_path = "./app/views/guide/checkinHistory.php";
        require_once "./app/views/layouts/guideHeader.php";
    }
}
