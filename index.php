<?php
session_start();
ob_start();

// SỬA LẠI ĐƯỜNG DẪN (Bỏ ../ đi)
require_once 'app/helpers/env.php';      // Cũ: ../app/helpers/env.php
require_once 'app/helpers/functions.php';

require_once 'app/core/Model.php';
require_once 'app/core/Controller.php';

require_once 'app/models/UserModel.php';
require_once 'app/models/TourModel.php';
// require_once 'app/models/TourModel.php';

require_once 'app/controllers/UserController.php';
require_once 'app/controllers/TourController.php';
require_once 'app/controllers/DashboardController.php';

// require_once 'app/controllers/TourController.php';


// 5. Lấy tham số act từ URL (mặc định là trang chủ)
$act = $_GET['act'] ?? '/';

// 6. Điều hướng bằng Switch (Dễ dùng hơn Match)
switch ($act) {

    // === TRANG CHỦ ===
    case '/':
    case 'home':
        (new DashboardController())->showDashboardCategory();
        break;

    // === QUẢN LÝ TÀI KHOẢN (USER) ===
    case 'listkh':
        (new UserController())->index();
        break;

    case 'addkh':
        (new UserController())->create();
        break;

    case 'storekh':
        (new UserController())->store();
        break;

    case 'editkh':
        (new UserController())->edit();
        break;

    case 'updatekh':
        (new UserController())->update();
        break;

    case 'deletekh':
        (new UserController())->delete();
        break;

    case 'login':
        (new UserController())->login();
        break;

    case 'checklogin':
        (new UserController())->handleLogin();
        break;

    case 'detailkh':
        (new UserController())->detail();
        break;

        // === DASHBOARD ===
    case 'dashboard':
        (new DashboardController())->showDashboardCategory();
        break;

        // === QUẢN LÝ TOUR ===
    case 'tour_list':
        (new TourController())->showTour();
        break;

    case 'add_tour':
        (new TourController())->addTour();
        break;

    case 'delete_tour':
        (new TourController())->deleteTour();
        break;

    case 'update_tour':
        (new TourController())->updateTour();
        break;

    // === MẶC ĐỊNH (404) ===
    default:
        echo "<h2>Lỗi 404: Trang không tồn tại!</h2>";
        // Hoặc include file 404 nếu đã tạo:
        // include '../app/views/404.php';
        break;
}

ob_end_flush();
