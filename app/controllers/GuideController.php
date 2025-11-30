<?php
class GuideController extends Controller {
    private $guideModel;
    private $userModel;

    public function __construct() {
        $this->guideModel = $this->model('GuideModel');
        $this->userModel = $this->model('UserModel'); // Dùng để check user trùng và delete
    }

    // 1. LIST
    public function index() {
        $listGuides = $this->guideModel->getAllGuides();
        $page_css = "assets/css/user.css"; // Tận dụng CSS của user
        $view_path = "app/views/guides/list.php"; 
        require_once "./app/views/layouts/main.php";
    }

    // 2. FORM ADD
    public function create() {
        $page_css = "assets/css/user.css";
        $view_path = "app/views/guides/add.php";
        require_once "./app/views/layouts/main.php";
    }

    // 3. STORE
    public function store() {
        if (isset($_POST['add_guide'])) {
            // Check trùng username bên bảng users
            if ($this->userModel->checkUsernameExists($_POST['username'])) {
                $_SESSION['error'] = "Tên đăng nhập đã tồn tại!";
                echo "<script>window.history.back();</script>";
                return;
            }

            // Upload ảnh
            $avatar = "";
            if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
                $safeName = str_replace(' ', '_', $_FILES['avatar']['name']);
                $avatar = time() . '_' . $safeName;
                move_uploaded_file($_FILES['avatar']['tmp_name'], "assets/uploads/" . $avatar);
            }

            // Data User
            $dataUser = [
                ':username' => $_POST['username'],
                ':password' => $_POST['password'], // Nên mã hóa MD5/Bcrypt
                ':full_name' => $_POST['full_name'],
                ':email' => $_POST['email'],
                ':phone' => $_POST['phone'],
                ':birthday' => $_POST['birthday'],
                ':avatar' => $avatar
            ];

            // Data Guide
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

    // 5. UPDATE
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
    public function detail() {
        $id = $_GET['id'] ?? 0;
        $guide = $this->guideModel->getGuideById($id);
        if ($guide) {
            $page_css = "assets/css/user.css";
            $view_path = "app/views/guides/detail.php";
            require_once "./app/views/layouts/main.php";
        } else {
            header('Location: index.php?act=list_guide');
        }
    }
    
    // 7. DELETE (Dùng luôn hàm của UserModel vì có Cascade)
    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = "Đã xóa HDV và tài khoản liên quan!";
        } else {
            $_SESSION['error'] = "Lỗi xóa dữ liệu!";
        }
        header('Location: index.php?act=list_guide');
    }
}