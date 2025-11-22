<?php
// app/controllers/UserController.php

class UserController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }

    // ==================================================
    // PHẦN 1: ADMIN QUẢN LÝ TÀI KHOẢN (CRUD)
    // ==================================================

    // 1. Danh sách tài khoản (Tương ứng 'listkh' trong mẫu)
    public function index() {
        $listkhachhang = $this->userModel->getAllUsers();
        // Load view và truyền dữ liệu
        $this->view('users/index', ['listkhachhang' => $listkhachhang]);
    }

    // 2. Form thêm mới (Tương ứng 'addkh' view)
    public function create() {
        $this->view('users/add');
    }

    // ==================================================
    // PHẦN 2: KHÁCH HÀNG (LOGIN / REGISTER)
    // ==================================================

    // Form đăng nhập
    public function login() {
        $this->view('auth/login');
    }

    // Xử lý đăng nhập (Tương ứng case 'dangnhap')
    public function handleLogin() {
        if (isset($_POST['dangnhap'])) {
            $user = $_POST['user'];
            $pass = $_POST['pass'];

            $checkuser = $this->userModel->checkUser($user, $pass);

            if (is_array($checkuser)) {
                $_SESSION['user'] = $checkuser; // Lưu session
                
                // Nếu là admin thì vào trang quản trị, user thì về trang chủ
                if ($checkuser['role'] == 1) {
                    header('Location: ' . BASE_URL . 'user/index');
                } else {
                    header('Location: ' . BASE_URL);
                }
            } else {
                $error = "Tài khoản hoặc mật khẩu sai!";
                $this->view('auth/login', ['error' => $error]);
            }
        }
    }

    // Đăng xuất
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . 'user/login');
    }
    public function edit() {
        // Cũ: $url = explode... (Bỏ)
        // Mới: Lấy từ ?id=...
        $id = $_GET['id'] ?? 0;

        if ($id > 0) {
            $khachhang = $this->userModel->getOne($id);
            $this->view('users/edit', ['khachhang' => $khachhang]);
        } else {
            echo "ID không hợp lệ";
        }
    }

    // Hàm UPDATE (Cập nhật)
    public function update() {
        // Lấy ID
        $id = $_POST['user_id'] ?? $_GET['id'];

        if (isset($_POST['capnhat']) && $id > 0) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $full_name = $_POST['full_name']; // Mới thêm
            $email = $_POST['email'];
            $phone = $_POST['phone'];         // Mới thêm
            $birthday = $_POST['birthday'];   // Mới thêm
            $role = $_POST['role'];

            // Gọi Model
            $this->userModel->updateUser($id, $username, $password, $full_name, $email, $phone, $birthday, $role);
            
            header('Location: index.php?act=listkh'); 
        }
    }

    // SỬA LẠI HÀM DELETE
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($id > 0) {
            $this->userModel->deleteUser($id);
        }
        // Chuyển hướng kiểu cũ
        header('Location: index.php?act=listkh');
    }

// Hàm STORE (Thêm mới)
    public function store() {
        if (isset($_POST['themoi'])) {
            // Lấy dữ liệu từ form
            $username = $_POST['username'];
            $password = $_POST['password']; // Nhớ mã hóa nếu cần
            $full_name = $_POST['full_name']; // Mới thêm
            $email = $_POST['email'];
            $phone = $_POST['phone'];         // Mới thêm
            $birthday = $_POST['birthday'];   // Mới thêm
            $role = $_POST['role'];

            // Gọi Model
            $this->userModel->insertUser($username, $password, $full_name, $email, $phone, $birthday, $role);
            
            header('Location: index.php?act=listkh');
        }
    }
}