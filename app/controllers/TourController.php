<?php

class TourController extends Controller
{
    public $tourModel;

    public function __construct()
    {
        $this->tourModel = $this->model('TourModel');
    }

    // 1. HIỆN DANH SÁCH TOUR THƯỜNG
public function showTour() {
    $keyword = $_GET['keyword'] ?? null;
    $filter_value = $_GET['category_id'] ?? null;

    // Truyền tham số vào Model
    $tour_list = $this->tourModel->getToursByType(0, $keyword, $filter_value);
    
    $categories = $this->tourModel->getAllCategories();
    
    $data = [
        'tour_list' => $tour_list,
        'category_filter' => $filter_value, 
        'keyword' => $keyword, // Truyền lại để giữ giá trị trong ô input
        'categories' => $categories
    ];

        extract($data);

        $view_path = './app/views/tours/tour_list.php';
        $page_css = "assets/css/tour.css";
        $page_title = "Danh sách Tour";

        require_once "./app/views/layouts/main.php";
    }

    // 2. HIỆN DANH SÁCH TOUR CUSTOM
    public function showCustomTours() {
        
    $keyword = $_GET['keyword'] ?? null;
    $tour_list = $this->tourModel->getToursByType(1, $keyword, null);
        $categories = $this->tourModel->getAllCategories();

        $data = ['tour_list' => $tour_list, 'categories' => $categories];
        extract($data);

        $view_path = './app/views/tours/custom_tour_list.php';
        $page_title = "Quản lý Tour Thiết kế";
        require_once "./app/views/layouts/main.php";
    }

    // 3. THÊM TOUR MỚI
    public function addTour()
    {
        $categories = $this->tourModel->getAllCategories() ?? [];
        $guides = $this->tourModel->getAllGuides(); // Nếu cần dùng
        $sticky_data = [];
        $error_occurred = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý upload ảnh
            $image_name = "";
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = "assets/uploads/tours/";
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                $safeName = str_replace(' ', '_', $_FILES['image']['name']);
                $image_name = time() . '_' . $safeName;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
            }

            $data = [
                'category_id'       => $_POST['category_id'] ?? 0,
                'tour_name'         => $_POST['tour_name'] ?? '',
                'short_description' => $_POST['short_description'] ?? '',
                'description'       => $_POST['description'] ?? '',
                'duration_days'     => $_POST['duration_days'] ?? 0,
                'base_price'        => $_POST['base_price'] ?? 0.0,
                'end_date'          => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'start_date'        => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'supplier'          => $_POST['supplier'] ?? '',
                'policy'            => $_POST['policy'] ?? '',
                'image_url'         => $image_name,
                'status'            => isset($_POST['status']) ? (int)$_POST['status'] : 1,
                'people'            => $_POST['people'] ?? 0
            ];

            if (empty($data['tour_name'])) {
                $_SESSION['error'] = "Tên tour không được để trống!";
                $error_occurred = true;
            }

            if (!$error_occurred) {
                if ($this->tourModel->addTourInfo($data)) {
                    $_SESSION['success'] = "Thêm tour thành công!";
                    header('Location: index.php?act=tour_list');
                    exit();
                } else {
                    $_SESSION['error'] = "Lỗi hệ thống.";
                    $error_occurred = true;
                }
            }
            if ($error_occurred) $sticky_data = $data;
        }

        $data_for_view = [
            'categories' => $categories,
            'sticky_data' => $sticky_data
        ];
        extract($data_for_view);

        $view_path = './app/views/tours/add_tour.php';
        $page_css = "assets/css/tour.css";
        $page_title = "Thêm Tour Mới";
        require_once './app/views/layouts/main.php';
    }

    // 4. CẬP NHẬT TOUR (Đã sửa lỗi logic if/else)
    public function updateTour()
    {
        $id = $_GET['id'] ?? null;
        $tour = $this->tourModel->getTourById($id);
        $categories = $this->tourModel->getAllCategories();

        if (!$tour) {
            $_SESSION['error'] = "Không tìm thấy Tour.";
            header('Location: index.php?act=tour_list');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Giữ ảnh cũ nếu không up ảnh mới
            $image_name = $tour['image_url'];
            $upload_dir = 'assets/uploads/tours/';

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $safeName = str_replace(' ', '_', $_FILES['image']['name']);
                $newImg = time() . '_' . $safeName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $newImg)) {
                    $image_name = $newImg;
                }
            }

            $data = [
                'tour_id'           => $id,
                'category_id'       => $_POST['category_id'] ?? $tour['category_id'],
                'tour_name'         => $_POST['tour_name'] ?? $tour['tour_name'],
                'short_description' => $_POST['short_description'] ?? $tour['short_description'],
                'description'       => $_POST['description'] ?? $tour['description'],
                'duration_days'     => $_POST['duration_days'] ?? $tour['duration_days'],
                'base_price'        => $_POST['base_price'] ?? $tour['base_price'],
                'image_url'         => $image_name,
                'end_date'          => !empty($_POST['end_date']) ? $_POST['end_date'] : $tour['end_date'],
                'start_date'        => !empty($_POST['start_date']) ? $_POST['start_date'] : $tour['start_date'],
                'supplier'          => $_POST['supplier'] ?? $tour['supplier'],
                'policy'            => $_POST['policy'] ?? $tour['policy'],
                'status'            => (isset($_POST['status']) && $_POST['status'] !== '') ? (int)$_POST['status'] : $tour['status'],
                'people'            => $_POST['people'] ?? $tour['people']
            ];

            if ($this->tourModel->updateTour($data)) {
                $_SESSION['success'] = "Cập nhật thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }

            // Redirect về danh sách sau khi update
            header('Location: index.php?act=tour_list');
            exit();
        }

        $data_for_view = [
            'tour' => $tour,
            'categories' => $categories
        ];
        extract($data_for_view);

        $view_path = './app/views/tours/update_tour.php';
        $page_title = "Cập nhật Tour";
        $page_css = "assets/css/tour.css";
        require_once './app/views/layouts/main.php';
    }

    // 5. XÓA TOUR
    public function deleteTour()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            if ($this->tourModel->deleteTour($id)) {
                $_SESSION['success'] = "Xóa tour thành công!";
            } else {
                $_SESSION['error'] = "Xóa thất bại!";
            }
        }
        header('Location: index.php?act=tour_list');
        exit();
    }

    // 6. CHI TIẾT TOUR
    public function detailTour()
    {
        $id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getTourById($id);

        if (!$tour) {
            $_SESSION['error'] = "Không tìm thấy thông tin Tour!";
            header('Location: index.php?act=tour_list');
            exit();
        }

        $categories = $this->tourModel->getAllCategories();
        $category_name = "Chưa phân loại";
        foreach ($categories as $cat) {
            if ($cat['category_id'] == $tour['category_id']) {
                $category_name = $cat['category_name'];
                break;
            }
        }

        $guide_name = "Xem trong Lịch khởi hành";

        $data_for_view = [
            'tour' => $tour,
            'category_name' => $category_name,
            'guide_name' => $guide_name
        ];

        extract($data_for_view);

        $view_path = './app/views/tours/tour_detail.php';
        $page_title = "Chi tiết Tour: " . htmlspecialchars($tour['tour_name']); 
        $page_css = "assets/css/tour.css";
        require_once './app/views/layouts/main.php';
    }

// 7. QUẢN LÝ LỊCH TRÌNH
    public function manageSchedules() {
        $tour_id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);
        
        $guides = $this->tourModel->getAllGuides(); 
        
        if (!$tour) {
            $_SESSION['error'] = "Không tìm thấy Tour!";
            header("Location: index.php?act=tour_list");
            exit;
        }

        // --- XỬ LÝ THÊM LỊCH ---
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_schedule'])) {
            
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date']; // Lấy từ input readonly
            $guide_id = !empty($_POST['guide_id']) ? $_POST['guide_id'] : null;

            // Kiểm tra trùng lịch
            if ($guide_id) {
                $is_available = $this->tourModel->checkGuideAvailability($guide_id, $start_date, $end_date);
                
                if (!$is_available) {
                    $guide_name = "HDV này"; 
                    foreach($guides as $g) {
                        if($g['guide_id'] == $guide_id) {
                            $guide_name = $g['full_name'];
                            break;
                        }
                    }
                    $_SESSION['error'] = "Không thể xếp lịch! <strong>$guide_name</strong> đang bận dẫn tour khác trong khung giờ này (hoặc chưa kết thúc tour trước).";
                    header("Location: index.php?act=tour_schedules&id=$tour_id");
                    exit;
                }
            }
            $data = [
                ':tour_id' => $tour_id,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':price' => $_POST['price'],
                ':stock' => $_POST['stock'],
                ':guide_id' => $guide_id
            ];
            
            $this->tourModel->addSchedule($data);
            $_SESSION['success'] = "Đã thêm lịch khởi hành thành công!";
            header("Location: index.php?act=tour_schedules&id=$tour_id");
            exit;
        } // <--- BẠN ĐÃ THIẾU DẤU ĐÓNG NÀY

        // --- XỬ LÝ XÓA LỊCH ---
        if (isset($_GET['delete_id'])) {
            $this->tourModel->deleteSchedule($_GET['delete_id']);
            $_SESSION['success'] = "Đã xóa lịch!";
            header("Location: index.php?act=tour_schedules&id=$tour_id");
            exit;
        }

        $schedules = $this->tourModel->getSchedules($tour_id);

        $view_path = './app/views/tours/manage_schedules.php';
        $page_title = "Quản lý lịch khởi hành";
        require_once './app/views/layouts/main.php';
    }

    // 8. BÁO GIÁ TOUR CUSTOM
    public function quoteTour() {
        $id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getTourById($id);
        $guides = $this->tourModel->getAllGuides();

        if (!$tour) {
            $_SESSION['error'] = "Không tìm thấy Tour!";
            header("Location: index.php?act=custom_tour_list");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $price = $_POST['price']; 
            $guide_id = $_POST['guide_id'];
            $note = $_POST['policy']; 
            
            $this->tourModel->updateQuote($id, $price, $guide_id, $note);

            $_SESSION['success'] = "Đã báo giá và cập nhật đơn hàng thành công!";
            header("Location: index.php?act=custom_tour_list");
            exit;
        }

        $view_path = './app/views/tours/quote.php';
        $page_title = "Báo giá Tour thiết kế";
        require_once "./app/views/layouts/main.php";
    }

    // 9. QUẢN LÝ BOOKING & LỊCH SỬ (Đã gộp 2 hàm booking thành 1)
    public function tourBookings() {
        $id = $_GET['id'] ?? 0;
        
        // Gọi Model Booking
        $bookingModel = $this->model('BookingModel'); 
        
        $tour = $this->tourModel->getTourById($id);
        $bookings = $bookingModel->getBookingsByTourId($id);

        $current_people = 0;
        foreach($bookings as $b) {
            if($b['status'] != 'Đã hủy') $current_people += $b['people'];
        }
        
        // Xử lý POST (Kích hoạt hoặc Kết thúc)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['activate_tour'])) {
                // Status 2 = Đang hoạt động
                $this->tourModel->updateTourStatus($id, 2);
                $bookingModel->updateAllBookingsStatus($id, 'Đã xác nhận');
                $_SESSION['success'] = "Đã kích hoạt Tour!";
                header("Location: index.php?act=tour_bookings&id=$id");
                exit;
            }
            if (isset($_POST['finish_tour'])) {
                // Status 3 = Hoàn thành
                $this->tourModel->updateTourStatus($id, 3);
                $bookingModel->updateAllBookingsStatus($id, 'Hoàn thành');
                $_SESSION['success'] = "Tour đã kết thúc!";
                header("Location: index.php?act=tour_bookings&id=$id");
                exit;
            }
        }

        $view_path = './app/views/tours/tour_bookings.php';
        require_once './app/views/layouts/main.php';
    }

public function passengerList() {
    $tour_id = $_GET['id'] ?? 0;
    $schedule_id = $_GET['schedule_id'] ?? 0;

    $tour = $this->tourModel->getTourById($tour_id);
    
    if (!$tour) {
        $_SESSION['error'] = "Không tìm thấy Tour!";
        header('Location: index.php?act=tour_list');
        exit;
    }

    $bookingModel = $this->model('BookingModel');

    // === TRƯỜNG HỢP 1: TOUR THIẾT KẾ (CUSTOM) ===
    if ($tour['tour_type'] == 1) {
        // Tour thiết kế chỉ có 1 booking duy nhất gắn liền, lấy thẳng danh sách
        // Sử dụng hàm getPassengersByTour đã viết trong Model
        $passengers = $bookingModel->getPassengersByTour($tour_id);
        
        $is_custom = true; // Cờ báo hiệu cho View
        $schedule = null;  // Custom tour không cần hiển thị thông tin schedule phức tạp
        
        $view_path = './app/views/tours/passenger_list.php';
        require_once './app/views/layouts/main.php';
    } 
    
    // === TRƯỜNG HỢP 2: TOUR CỐ ĐỊNH (STANDARD) ===
    else {
        // Nếu chưa chọn ngày khởi hành -> Chuyển sang trang chọn ngày
        if ($schedule_id == 0) {
            $schedules = $this->tourModel->getSchedules($tour_id);
            $view_path = './app/views/tours/select_schedule_passengers.php'; 
            /* Lưu ý: Bạn cần có file view select_schedule_passengers.php 
               hoặc tái sử dụng manage_schedules.php nhưng chỉ hiện nút "Xem khách" */
            require_once './app/views/layouts/main.php';
        } 
        // Nếu đã có ngày khởi hành -> Xem danh sách
        else {
            $schedule = $this->tourModel->getScheduleById($schedule_id);
            $passengers = $bookingModel->getPassengersBySchedule($schedule_id);
            
            $is_custom = false;
            $view_path = './app/views/tours/passenger_list.php';
            require_once './app/views/layouts/main.php';
        }
    }
}
}