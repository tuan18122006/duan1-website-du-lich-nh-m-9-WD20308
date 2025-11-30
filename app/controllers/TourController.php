<?php

class TourController extends Controller
{
    public $tourModel;

    public function __construct()
    {
        $this->tourModel = $this->model('TourModel');
    }

    // --- 1. HIỂN THỊ DANH SÁCH TOUR ---
    public function showTour()
    {
        $filter_value = $_GET['category_filter'] ?? 'Tất cả';
        $tour_list = [];

        // Lấy danh sách tour dựa theo bộ lọc
        if ($filter_value === 'Tất cả' || $filter_value === 'all') {
            $tour_list = $this->tourModel->getAllTour();
        } else if (is_numeric($filter_value)) {
            $category_id = (int)$filter_value;
            $tour_list = $this->tourModel->getToursByCategoryId($category_id);
        } else {
            $tour_list = $this->tourModel->getAllTour();
        }

        // Lấy danh mục để hiển thị dropdown
        $categories = $this->tourModel->getAllCategories();

        $data = [
            'tour_list' => $tour_list,
            'category_filter' => $filter_value,
            'categories' => $categories 
        ];

        // --- SỬA LẠI ĐOẠN NÀY ---
        // 1. Giải nén dữ liệu để view con dùng được
        extract($data);

        // 2. Định nghĩa view con và CSS (nếu có)
        $view_path = './app/views/tours/tour_list.php';
        $page_css = "assets/css/tour.css";
        $page_title = "Danh sách Tour";
        
        // 3. Gọi Layout chính (Layout sẽ tự include view_path vào giữa)
        require_once "./app/views/layouts/main.php";
    }

    // --- 2. THÊM TOUR MỚI ---
    public function addTour()
    {
        $categories = $this->tourModel->getAllCategories() ?? [];
        
        // Lấy danh sách HDV (Để không bị lỗi undefined variable khi view load)
        $guides = $this->tourModel->getAllGuides(); 

        $sticky_data = [];
        $error_occurred = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý upload ảnh đại diện
            $image_name = "";
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = "assets/uploads/tours/";
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $safeName = str_replace(' ', '_', $_FILES['image']['name']);
                $image_name = time() . '_' . $safeName;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                    $_SESSION['error'] = "Lỗi file ảnh.";
                    $error_occurred = true;
                }
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
                'people'            => $_POST['people'] ?? 0,
                'guide_id'          => !empty($_POST['guide_id']) ? $_POST['guide_id'] : null
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

        // Chuẩn bị dữ liệu
        $data_for_view = [
            'categories' => $categories,
            'guides' => $guides,
            'sticky_data' => $sticky_data
        ];
        extract($data_for_view);

        // Gọi Layout
        $view_path = './app/views/tours/add_tour.php';
        $page_css = "assets/css/tour.css";
        $page_title = "Thêm Tour Mới";
        require_once './app/views/layouts/main.php';
    }

    // --- 3. CẬP NHẬT TOUR ---
    public function updateTour()
    {
        $id = $_GET['id'] ?? null;

        $tour = $this->tourModel->getTourById($id);
        $categories = $this->tourModel->getAllCategories();
        $guides = $this->tourModel->getAllGuides(); // Lấy danh sách HDV

        if (!$tour) {
            $_SESSION['error'] = "Không tìm thấy Tour.";
            header('Location: index.php?act=tour_list');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                // Fix lỗi không cập nhật trạng thái 0
                'status'            => (isset($_POST['status']) && $_POST['status'] !== '') ? (int)$_POST['status'] : $tour['status'],
                'people'            => $_POST['people'] ?? $tour['people'],
                'guide_id'          => !empty($_POST['guide_id']) ? $_POST['guide_id'] : null
            ];

            if ($this->tourModel->updateTour($data)) {
                $_SESSION['success'] = "Cập nhật thành công!";
                // Cập nhật lại biến $tour để hiển thị ngay
                $tour = array_merge($tour, $data); 
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }
            
            // Redirect về danh sách
            header('Location: index.php?act=tour_list');
            exit();
        }

        $data_for_view = [
            'tour' => $tour,
            'categories' => $categories,
            'guides' => $guides
        ];
        extract($data_for_view);

        // Gọi Layout
        $view_path = './app/views/tours/update_tour.php';
        $page_title = "Cập nhật Tour";
        $page_css = "assets/css/tour.css";
        require_once './app/views/layouts/main.php';
    }

    // --- 4. XÓA TOUR ---
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

        public function detailTour()
    {
        $id = $_GET['id'] ?? 0;

        // 1. Lấy thông tin Tour
        $tour = $this->tourModel->getTourById($id);

        if (!$tour) {
            $_SESSION['error'] = "Không tìm thấy thông tin Tour!";
            header('Location: index.php?act=tour_list');
            exit();
        }

        // 2. Lấy danh mục để tìm tên danh mục
        $categories = $this->tourModel->getAllCategories();
        $category_name = "Chưa phân loại";
        foreach ($categories as $cat) {
            if ($cat['category_id'] == $tour['category_id']) {
                $category_name = $cat['category_name'];
                break;
            }
        }

        // 3. Lấy danh sách HDV để tìm tên HDV
        $guides = $this->tourModel->getAllGuides();
        $guide_name = "Chưa chỉ định";
        if (!empty($tour['guide_id'])) {
            foreach ($guides as $g) {
                if ($g['guide_id'] == $tour['guide_id']) {
                    $guide_name = $g['full_name'];
                    break;
                }
            }
        }

        // 4. Chuẩn bị dữ liệu gửi sang View
        $data_for_view = [
            'tour' => $tour,
            'category_name' => $category_name,
            'guide_name' => $guide_name
        ];
        
        extract($data_for_view);

        // 5. Gọi View
        $view_path = './app/views/tours/tour_detail.php';
        $page_title = "Chi tiết Tour: " . htmlspecialchars($tour['tour_name']); // Tiêu đề tab trình duyệt
        $page_css = "assets/css/tour.css";
        require_once './app/views/layouts/main.php';
    }
    
public function tourBookings() {
    $id = $_GET['id'] ?? 0;
    
    // 1. Lấy thông tin Tour
    $tour = $this->tourModel->getTourById($id);
    
    // 2. Lấy danh sách khách đã đặt của Tour này
    // Lưu ý: Cần khởi tạo BookingModel trong __construct nếu chưa có
    // $this->bookingModel = $this->model('BookingModel');
    $bookings = $this->model('BookingModel')->getBookingsByTourId($id);

    // 3. Tính toán số lượng khách
    $current_people = 0;
    foreach($bookings as $b) {
        if($b['status'] != 'Đã hủy') {
            $current_people += $b['people'];
        }
    }
    
    // 4. Xử lý kích hoạt Tour (Nếu Admin bấm nút kích hoạt tại đây)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activate_tour'])) {
        $this->tourModel->updateTourStatus($id, 2); // 2 = Hoạt động
        $_SESSION['success'] = "Đã kích hoạt tour thành công!";
        header("Location: index.php?act=tour_bookings&id=$id");
        exit;
    }

    $view_path = './app/views/tours/tour_bookings.php';
    require_once './app/views/layouts/main.php';
}
}