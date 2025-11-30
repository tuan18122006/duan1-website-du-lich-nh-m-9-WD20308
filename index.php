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
require_once 'app/models/GuideModel.php';
require_once 'app/models/LoginModel.php';
require_once 'app/models/GuideModel.php';
require_once 'app/models/BookingModel.php';
// require_once 'app/models/TourModel.php';

require_once 'app/controllers/UserController.php';
require_once 'app/controllers/TourController.php';
require_once 'app/controllers/DashboardController.php';
require_once 'app/controllers/GuideController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/GuideController.php';
require_once 'app/controllers/BookingController.php';

// require_once 'app/controllers/TourController.php';


// 5. Lấy tham số act từ URL (mặc định là trang chủ)
$act = $_GET['act'] ?? 'login';

// 6. Điều hướng bằng Switch (Dễ dùng hơn Match)
switch ($act) {

    // === TRANG CHỦ ===
    // === LOGIN ===
    case 'login':             // Hiển thị form login
        (new LoginController())->index();
        break;

    case 'login_action':      // Xử lý login
        (new LoginController())->login();
        break;

    case 'logout':            // Đăng xuất
        (new LoginController())->logout();
        break;

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

    // case 'login':
    //     (new UserController())->login();
    //     break;

    // case 'checklogin':
    //     (new UserController())->handleLogin();
    //     break;

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
    case 'detail_tour':
        (new TourController())->detailTour();
        break;
case 'tour_bookings':
        (new TourController())->tourBookings();
        break;


            // === QUẢN LÝ HDV ===
    case 'list_guide':
        (new GuideController())->index();
        break;
    
    case 'add_guide':
        (new GuideController())->create();
        break;

    case 'store_guide':
        (new GuideController())->store();
        break;

    case 'edit_guide':
        (new GuideController())->edit();
        break;

    case 'update_guide':
        (new GuideController())->update();
        break;

    case 'detail_guide':
        (new GuideController())->detail();
        break;

    case 'delete_guide':
        (new GuideController())->delete();
        break;


    // booking //
    case 'booking_list':
        (new BookingController())->index();
        break;
    case 'booking_add':
        (new BookingController())->add();
        break;
    case 'booking_detail':
        (new BookingController())->detail();
        break;

    default:
        echo "<h2>Lỗi 404: Trang không tồn tại!</h2>";
        break;
}

ob_end_flush();