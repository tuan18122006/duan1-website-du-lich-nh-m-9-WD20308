<?php
class LoginController extends Controller
{
    private $loginModel;

    public function __construct()
    {
        $this->loginModel = $this->model('LoginModel');
    }

    // Hiển thị trang login
    public function index()
    {
        $this->view('login/login');
    }

    // Xử lý đăng nhập
    public function login()
    {
        // Nhận dữ liệu từ form
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Kiểm tra user trong database
        $user = $this->loginModel->checkLogin($username, $password);

        if ($user) {
            // Đăng nhập thành công
            $_SESSION['user'] = $user;
            header("Location: index.php?act=dashboard");
            exit;
        } else {
            // Sai thông tin → trả lại form cùng thông báo lỗi
            $error = "Sai username hoặc password";
            $this->view('login/login', ['error' => $error]);
        }
    }

    // Đăng xuất
    public function logout()
    {
        session_destroy();        // Hủy session
        header("Location: index.php?act=login");
        exit;
    }
}
