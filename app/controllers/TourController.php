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
        $page_css = "assets/css/tour.css";
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
                'price' => $_POST['price'] ?? 0.0,
                'end_location' => $_POST['end_location'] ?? '',
                'start_location' => $_POST['start_location'] ?? '',
                'supplier' => $_POST['supplier'] ?? '',
                'policy' => $_POST['policy'] ?? '',
                'image_url' => $image_name,
                'status' => $_POST['status'] ?? 'Hoạt động',
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

        $tour = $this->tourModel->getTourById($id);

        $categories = $this->tourModel->getAllCategories();

        if (!$tour) {
            $_SESSION['error'] = "Không tìm thấy Tour cần chỉnh sửa.";
            header('Location: index.php?act=tour_list');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $tour_name = $_POST['tour_name'] ?? $tour['tour_name'];
            $category_id = $_POST['category_id'] ?? $tour['category_id'];
            $price = $_POST['price'] ?? $tour['price'];
            $description = $_POST['description'] ?? $tour['description'];
            $short_description = $_POST['short_description'] ?? $tour['short_description'];
            $duration_days = $_POST['duration_days'] ?? $tour['duration_days'];
            $end_location = $_POST['end_location'] ?? $tour['end_location'];
            $start_location = $_POST['start_location'] ?? $tour['start_location'];
            $supplier = $_POST['supplier'] ?? $tour['supplier'];
            $policy = $_POST['policy'] ?? $tour['policy'];
            $status = $_POST['status'] ?? $tour['status'];


            $image_name = $tour['image_url'];
            $upload_dir = PATH_ROOT . 'assets/uploads/tours/';

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

                if (!empty($tour['image_url']) && file_exists($upload_dir . $tour['image_url'])) {
                    unlink($upload_dir . $tour['image_url']);
                }

                $safeName = str_replace(' ', '_', $_FILES['image']['name']);
                $image_name = time() . '_' . $safeName;
                $upload_path = $upload_dir . $image_name;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $_SESSION['error'] = "Lỗi khi upload ảnh mới.";
                    $image_name = $tour['image_url'];
                }
            }

            $data = [
                'tour_id' => $id,
                'category_id' => $category_id,
                'tour_name' => $tour_name,
                'short_description' => $short_description,
                'description' => $description,
                'duration_days' => $duration_days,
                'price' => $price,
                'image_url' => $image_name,
                'end_location' => $end_location,
                'start_location' => $start_location,
                'supplier' => $supplier,
                'policy' => $policy,
                'status' => $status
            ];

            if ($this->tourModel->updateTour($data)) {
                $_SESSION['success'] = "Cập nhật Tour ID: {$id} thành công!";
            } else {
                $_SESSION['error'] = "Lỗi hệ thống khi cập nhật Tour ID: {$id}.";
            }

            header('Location: index.php?act=tour_list');
            exit();
        }

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
