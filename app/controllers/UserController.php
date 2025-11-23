<?php
// app/controllers/UserController.php

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
    }

    // --- 1. DANH SÁCH TÀI KHOẢN (LIST) ---
    public function index()
    {
        $listkhachhang = $this->userModel->getAllUsers();
<<<<<<< HEAD
        $view_path = "./app/views/users/index.php";

        require_once "./app/views/layouts/main.php";
=======
        $this->view('users/index', ['listkhachhang' => $listkhachhang]);
>>>>>>> 207b1e9 (layout)
    }

    // --- 2. FORM THÊM MỚI (CREATE) ---
    public function create()
    {
        $this->view('users/add');
    }

    // --- 3. XỬ LÝ THÊM MỚI (STORE) ---
    public function store()
    {
        if (isset($_POST['themoi'])) {
            $username = $_POST['username'];

            // Kiểm tra trùng tên đăng nhập
            if ($this->userModel->checkUsernameExists($username)) {
                $_SESSION['error'] = "Tên đăng nhập '$username' đã tồn tại!";
                echo "<script>window.history.back();</script>";
                return;
            }

            $password = $_POST['password'];
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $birthday = $_POST['birthday'];
            $role = $_POST['role'];

            // Xử lý upload ảnh
            $avatar = "";
            if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
                // Đổi tên file để tránh trùng & lỗi ký tự đặc biệt
                $safeName = str_replace(' ', '_', $_FILES['avatar']['name']);
                $avatar = time() . '_' . $safeName;
                move_uploaded_file($_FILES['avatar']['tmp_name'], "assets/uploads/" . $avatar);
            }

            // Gọi Model thêm mới
            if ($this->userModel->insertUser($username, $password, $full_name, $email, $phone, $birthday, $role, $avatar)) {
                $_SESSION['success'] = "Thêm tài khoản mới thành công!";
            } else {
                $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại!";
            }

            header('Location: index.php?act=listkh');
        }
    }

    // --- 4. FORM SỬA (EDIT) ---
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $khachhang = $this->userModel->getOne($id);
            $this->view('users/edit', ['khachhang' => $khachhang]);
        } else {
            $_SESSION['error'] = "Không tìm thấy tài khoản!";
            header('Location: index.php?act=listkh');
        }
    }

    // --- 5. XỬ LÝ CẬP NHẬT (UPDATE) ---
    public function update()
    {
        $id = $_POST['user_id'] ?? $_GET['id'] ?? 0;

        if (isset($_POST['capnhat']) && $id > 0) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $birthday = $_POST['birthday'];
            $role = $_POST['role'];

            // Lấy ảnh cũ mặc định
            $avatar = $_POST['old_avatar'] ?? "";

            // Nếu có chọn ảnh mới thì upload và lấy tên mới
            if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
                $safeName = str_replace(' ', '_', $_FILES['avatar']['name']);
                $avatar = time() . '_' . $safeName;
                move_uploaded_file($_FILES['avatar']['tmp_name'], "assets/uploads/" . $avatar);
            }

            // Gọi Model cập nhật (Truyền đủ 9 tham số bao gồm avatar)
            if ($this->userModel->updateUser($id, $username, $password, $full_name, $email, $phone, $birthday, $role, $avatar)) {
                $_SESSION['success'] = "Cập nhật tài khoản thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }

            header('Location: index.php?act=listkh');
        }
    }

    // --- 6. XÓA (DELETE) ---
    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            if ($this->userModel->deleteUser($id)) {
                $_SESSION['success'] = "Xóa tài khoản thành công!";
            } else {
                $_SESSION['error'] = "Xóa thất bại!";
            }
        }
        header('Location: index.php?act=listkh');
    }

    // --- 7. XEM CHI TIẾT (DETAIL) ---
    public function detail()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $khachhang = $this->userModel->getOne($id);
            if ($khachhang) {
                $this->view('users/detail', ['khachhang' => $khachhang]);
            } else {
                $_SESSION['error'] = "Không tìm thấy thông tin người dùng!";
                header('Location: index.php?act=listkh');
            }
        } else {
            header('Location: index.php?act=listkh');
        }
    }

    // --- 8. ĐĂNG NHẬP (LOGIN) ---
    public function login()
    {
        include 'app/views/clients/taikhoan/dangnhap.php';
    }

    public function handleLogin()
    {
        if (isset($_POST['dangnhap'])) {
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $checkuser = $this->userModel->checkUser($user, $pass);

            if (is_array($checkuser)) {
                $_SESSION['user'] = $checkuser;
                $_SESSION['success'] = "Đăng nhập thành công!";

                if ($checkuser['role'] == 1) {
                    header('Location: index.php?act=listkh');
                } else {
                    header('Location: index.php');
                }
            } else {
                $thongbao = "Tài khoản hoặc mật khẩu sai!";
                include 'app/views/clients/taikhoan/dangnhap.php';
            }
        }
    }
    // --- 9. ĐĂNG XUẤT (LOGOUT) ---
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php');
    }
}
