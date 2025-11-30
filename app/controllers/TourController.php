<?php

class TourController extends Controller
{
    public $tourModel;

    public function __construct()
    {
        $this->tourModel = new TourModel();
    }

    public function showTour()
    {
        $filter_value = $_GET['category_filter'] ?? 'Tất cả';
        $tour_list = [];

        if ($filter_value === 'Tất cả' || $filter_value === 'all') {
            $tour_list = $this->tourModel->getAllTour();
        } else if (is_numeric($filter_value)) {
            $category_id = (int)$filter_value;
            $tour_list = $this->tourModel->getToursByCategoryId($category_id);
        } else {
            $tour_list = $this->tourModel->getAllTour();
        }

        $data = [
            'tour_list' => $tour_list,
            'category_filter' => $filter_value
        ];

        extract($data);

        $view_path = "./app/views/tours/tour_list.php";
        $page_css = "./assets/css/tour.css";
        require_once "./app/views/layouts/main.php";
    }

    public function addTour()
    {
        $categories = $this->tourModel->getAllCategories() ?? [];
        $sticky_data = [];
        $error_occurred = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $image_name = "";
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = PATH_ROOT . "assets/uploads/tours/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $safeName = str_replace(' ', '_', $_FILES['image']['name']);
                $image_name = time() . '_' . $safeName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                    $_SESSION['error'] = "Lỗi khi di chuyển file ảnh. Vui lòng kiểm tra quyền thư mục.";
                    $image_name = "";
                    $error_occurred = true;
                }
            }

            $data = [
                'category_id' => $_POST['category_id'] ?? 0,
                'tour_name' => $_POST['tour_name'] ?? '',
                'short_description' => $_POST['short_description'] ?? '',
                'description' => $_POST['description'] ?? '',
                'duration_days' => $_POST['duration_days'] ?? 0,
                'base_price' => $_POST['base_price'] ?? 0.0,
                'end_date' => $_POST['end_date'] ?? '',
                'start_date' => $_POST['start_date'] ?? '',
                'supplier' => $_POST['supplier'] ?? '',
                'policy' => $_POST['policy'] ?? '',
                'image_url' => $image_name,
                'status' => isset($_POST['status']) ? (int)$_POST['status'] : 1,
                'people' => $_POST['people'] ?? 0,
            ];

            if (empty($data['tour_name'])) {
                $_SESSION['error'] = "Tên tour không được để trống!";
                $error_occurred = true;
            }

            if (!$error_occurred) {
                if ($this->tourModel->addTourInfo($data)) {
                    $_SESSION['success'] = "Thêm tour mới thành công!";
                    header('Location: index.php?act=tour_list');
                    exit();
                } else {
                    $_SESSION['error'] = "Lỗi hệ thống, không thể thêm tour vào CSDL.";
                    $error_occurred = true;
                }
            }

            if ($error_occurred) {
                $sticky_data = $data;
            }
        }

        $data_for_view = array_merge(['categories' => $categories], $sticky_data);
        extract($data_for_view);

        $view_path = './app/views/tours/add_tour.php';
        $page_css = "assets/css/tour.css";
        require_once './app/views/layouts/main.php';
    }


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
    }


    public function updateTour()
    {
        $id = $_GET['id'] ?? null;

        // 1. Lấy thông tin Tour từ DB
        $tour = $this->tourModel->getTourById($id);
        $categories = $this->tourModel->getAllCategories();

        // 2. Kiểm tra tồn tại
        if (!$tour) {
            $_SESSION['error'] = "Không tìm thấy Tour cần chỉnh sửa.";
            header('Location: index.php?act=tour_list');
            exit();
        }

        // 3. Xử lý khi Submit form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Nhận dữ liệu và xử lý ảnh
            $image_name = $tour['image_url'];
            $upload_dir = PATH_ROOT . 'assets/uploads/tours/';

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // ... (code upload ảnh giữ nguyên) ...
                $safeName = str_replace(' ', '_', $_FILES['image']['name']);
                $image_name = time() . '_' . $safeName;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                    $image_name = $tour['image_url']; // Nếu lỗi thì giữ ảnh cũ
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
                
                // Sử dụng biến đã xử lý NULL ở trên
                'end_date'          => $end_date ?? $tour['end_date'],
                'start_date'        => $start_date ?? $tour['start_date'],
                
                'supplier'          => $_POST['supplier'] ?? $tour['supplier'],
                'policy'            => $_POST['policy'] ?? $tour['policy'],
                'status'            => isset($_POST['status']) && $_POST['status'] !== '' ? (int)$_POST['status'] : ($tour['status'] ?? 0),
                'people'            => $_POST['people'] ?? $tour['people'],
            ];

            if ($this->tourModel->updateTour($data)) {
                $_SESSION['success'] = "Cập nhật Tour thành công!";
                // Cập nhật lại biến $tour để hiển thị dữ liệu mới nhất ở view nếu không redirect ngay
                $tour = array_merge($tour, $data); 
            } else {
                $_SESSION['error'] = "Lỗi hệ thống khi cập nhật!";
            }

            // Redirect về danh sách để thấy thay đổi
            header('Location: index.php?act=tour_list');
            exit();
        }

        // 4. Chuẩn bị dữ liệu cho View
        // Biến $tour ở đây chắc chắn có dữ liệu (từ DB hoặc sau khi update)
        $data_for_view = [
            'tour' => $tour,
            'categories' => $categories
        ];

        extract($data_for_view);

        $view_path = './app/views/tours/update_tour.php';
        $page_css = "./assets/css/tour.css";
        require_once './app/views/layouts/main.php';
    }
}