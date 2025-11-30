<?php
class DashboardController extends Controller
{
    // Test dashboard
    public function index()
    {
        echo "Dashboard hoạt động ✔ – MVC OK!";
    }

    // Hiển thị dashboard chính
    public function showDashboardCategory()
    {
        // Kiểm tra user đã login chưa
        if(empty($_SESSION['user'])){
            header("Location: index.php?act=login");
            exit;
        }

        // Đường dẫn view
        $view_path = "./app/views/dashboard/dashboard.php"; 

        // Load layout chính
        require_once "./app/views/layouts/main.php";
    }
}
