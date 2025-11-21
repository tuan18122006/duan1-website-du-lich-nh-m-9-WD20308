<?php
session_start();

// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)
// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controller/HomeController.php';

// Require toàn bộ file Models


// Lấy tham số mode từ URL để phân quyền client/admin
match($c) {
    '' => (new HomeController)-> index(),
    default => (new HomeController)->error404()
}

?>