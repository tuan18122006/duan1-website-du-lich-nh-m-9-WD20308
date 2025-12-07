<?php
class LoginController extends Controller
{
    private $loginModel;

    public function __construct()
    {
        $this->loginModel = $this->model('LoginModel');
    }

    public function index()
    {
        $this->view('login/login');
    }

    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->loginModel->checkLogin($username, $password);

        if ($user) {

            $_SESSION['user'] = [
                'id'       => $user['id'],
                'name'     => $user['fullname'] ?? $user['username'],
                'role'     => $user['role'],
                'guide_id' => $user['guide_id'] ?? null
            ];

            if ($user['role'] == 1) {
                header("Location: index.php?act=dashboard");
                exit;
            }

            if ($user['role'] == 2) {
                header("Location: index.php?act=guide_home");
                exit;
            }
        } else {
            $this->view('login/login', ['error' => 'Sai username hoặc password']);
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php?act=login");
        exit;
    }
}
