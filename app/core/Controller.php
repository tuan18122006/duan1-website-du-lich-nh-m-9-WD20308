<?php

class Controller 
{
    protected function view($view, $data = [])
    {
        extract($data);
        require_once "app/views/$view.php";
    }

    public function model($model) {
        // Vì index.php đang ở gốc, nên đường dẫn vào app là trực tiếp
        require_once "app/models/" . $model . ".php"; 
        return new $model;
    }

        // Chỉ admin mới được vào
    public static function requireAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
            header("Location: index.php?act=home");
            exit();
        }
    }

    // Chỉ HDV mới được vào
    public static function requireGuide() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header("Location: index.php?act=guide_home");
            exit();
        }
    }
}
