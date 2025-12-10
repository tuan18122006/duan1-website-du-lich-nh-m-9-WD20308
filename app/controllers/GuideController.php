<?php
class GuideController extends Controller
{
    private $guideModel;
    private $userModel;
    public $tourModel;

    public function __construct()
    {
        $this->guideModel = $this->model('GuideModel');
        $this->userModel = $this->model('UserModel');
        $this->tourModel = $this->model('TourModel');
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

public function dashboard() {
        // 1. Kiểm tra quyền & Lấy ID
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header("Location: index.php"); exit;
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

    // 9. TOUR CỦA TÔI
    public function myTour()
    {
        // Logic lấy ID giống Dashboard
        $guide_id = $_SESSION['user']['guide_id'] ?? null;
        if (!$guide_id) {
             $guide = $this->guideModel->getGuideById($_SESSION['user']['user_id']);
             $guide_id = $guide['guide_id'] ?? 0;
        }

        $tours = $this->tourModel->getToursByGuide($guide_id);

        $view_path = "./app/views/guide/myTour.php";
        require_once "./app/views/layouts/guideHeader.php";
    }
    // 10. XEM DANH SÁCH KHÁCH (Giao diện HDV)
    public function passengerList() {
        // Kiểm tra quyền HDV
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header("Location: index.php"); exit;
        }

        $tour_id = $_GET['id'] ?? 0;
        $schedule_id = $_GET['schedule_id'] ?? 0;

        // Lấy thông tin Tour
        $tour = $this->tourModel->getTourById($tour_id);
        
        // Lấy danh sách khách (Sử dụng Model Booking đã có)
        $bookingModel = $this->model('BookingModel');
        $passengers = $bookingModel->getPassengersBySchedule($schedule_id);

        // Gọi View riêng cho HDV
        $view_path = "./app/views/guide/passenger_list.php";
        require_once "./app/views/layouts/guideHeader.php"; 
    }
    // Thêm vào GuideController.php

// ... (Các hàm khác giữ nguyên) ...

    // 11. TRANG ĐIỂM DANH (Dành cho HDV)
    public function attendance() {
        // Kiểm tra quyền HDV
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header("Location: index.php"); exit;
        }

        $schedule_id = $_GET['schedule_id'] ?? 0;
        $tour_id = $_GET['id'] ?? 0;
        
        $bookingModel = $this->model('BookingModel');

        // --- XỬ LÝ KHI BẤM NÚT LƯU ---
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Bước 1: Reset toàn bộ khách trong lịch này về trạng thái "Chưa đến" (0)
            // Lý do: Để xử lý trường hợp bỏ tick (bỏ chọn) một khách đã điểm danh trước đó.
            $bookingModel->resetCheckinForSchedule($schedule_id);

            // Bước 2: Cập nhật những người được tick chọn thành "Có mặt" (1)
            if (isset($_POST['checked_passengers']) && is_array($_POST['checked_passengers'])) {
                foreach ($_POST['checked_passengers'] as $passenger_id) {
                    $bookingModel->updatePassengerCheckin($passenger_id, 1);
                }
            }
            
            $_SESSION['success'] = "Cập nhật điểm danh thành công!";
            // Load lại trang để thấy kết quả
            header("Location: index.php?act=guide_attendance&id=$tour_id&schedule_id=$schedule_id");
            exit;
        }

        // --- HIỂN THỊ DỮ LIỆU ---
        $tour = $this->tourModel->getTourById($tour_id);
        
        // Gọi hàm mới vừa viết ở Model
        $passengerList = $bookingModel->getPassengersByScheduleWithCheckin($schedule_id);

        $view_path = "./app/views/guide/attendance.php";
        require_once "./app/views/layouts/guideHeader.php";
    }

}