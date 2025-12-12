<?php
session_start();
ob_start();

// 1. IMPORT CẤU HÌNH & HÀM HỖ TRỢ
require_once 'app/helpers/env.php';
require_once 'app/helpers/functions.php';

// 2. IMPORT CORE
require_once 'app/core/Model.php';
require_once 'app/core/Controller.php';

// 3. IMPORT MODELS
require_once 'app/models/UserModel.php';
require_once 'app/models/TourModel.php';
require_once 'app/models/GuideModel.php';
require_once 'app/models/LoginModel.php';
require_once 'app/models/BookingModel.php';

// 4. IMPORT CONTROLLERS
require_once 'app/controllers/UserController.php';
require_once 'app/controllers/TourController.php';
require_once 'app/controllers/DashboardController.php';
require_once 'app/controllers/GuideController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/BookingController.php';
require_once 'app/controllers/WelcomeController.php';



// 5. LẤY THAM SỐ ACT TỪ URL
if (isset($_GET['act'])) {
    $act = $_GET['act'];
} else {

    // 🔹 Nếu chưa có SESSION và chưa có act => vào trang welcome
    if (!isset($_SESSION['user'])) {
        $act = 'welcome';   // Trang đầu tiên khi chạy website
    } else {

        // 🔹 Nếu đã đăng nhập -> chuyển theo role
        if ($_SESSION['user']['role'] == 2) {
            $act = 'guide_home';    // HDV
        } else {
            $act = 'dashboard';     // Admin
        }
    }
}

// 6. ĐIỀU HƯỚNG ROUTER
switch ($act) {
    case 'welcome':
        (new WelcomeController())->index();
        break;
    // ===========================
    // 1. AUTHENTICATION (Đăng nhập/Xuất)
    // ===========================
    case 'login':
        (new LoginController())->index();
        break;

    case 'login_action':
        (new LoginController())->login();
        break;

    case 'logout':
        (new LoginController())->logout();
        break;

    // ===========================
    // 2. DASHBOARD (Thống kê Admin)
    // ===========================
    case 'home':
    case 'dashboard':
        (new DashboardController())->showDashboardCategory();
        break;

    // ===========================
    // 3. QUẢN LÝ KHÁCH HÀNG (USER)
    // ===========================
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
    case 'detailkh':
        (new UserController())->detail();
        break;

    // ===========================
    // 4. QUẢN LÝ TOUR (Admin)
    // ===========================

    // --- Tour Chuẩn ---
    case 'tour_list':
        (new TourController())->showTour();
        break;
    case 'add_tour':
        (new TourController())->addTour();
        break;
    case 'update_tour':
        (new TourController())->updateTour();
        break;
    case 'delete_tour':
        (new TourController())->deleteTour();
        break;
    case 'detail_tour':
        (new TourController())->detailTour();
        break;

    case 'tour_passenger_list':
        (new TourController())->passengerList();
        break;
    case 'passenger_list':
        (new TourController())->passengerList();
        break;

    // --- Tour Thiết kế (Custom) ---
    case 'custom_tour_list':
        (new TourController())->showCustomTours();
        break;
    case 'tour_quote':
        (new TourController())->quoteTour();
        break; // Báo giá

    // --- Vận hành Tour (Lịch trình, Booking, Lịch sử) ---
    case 'tour_schedules':
        (new TourController())->manageSchedules();
        break; // Quản lý lịch khởi hành
    case 'tour_bookings':
        (new TourController())->tourBookings();
        break;    // Quản lý khách trong tour
    case 'tour_history':
        (new TourController())->tourHistory();
        break;     // Lịch sử tour

    // ===========================
    // 5. QUẢN LÝ HƯỚNG DẪN VIÊN (Admin quản lý HDV)
    // ===========================
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

    // ===========================
    // 6. GIAO DIỆN DÀNH CHO HDV (Guide Panel) - ĐÃ SỬA LẠI
    // ===========================
    case 'guide_home':
        // Gọi hàm dashboard mới trong GuideController
        (new GuideController())->dashboard();
        break;

    case 'my_tour':
        (new GuideController())->myTour();
        break;
    case 'checkin':
        (new GuideController())->checkin();
        break;
    case 'checkin_history':
        (new GuideController())->checkinHistory();
        break;
    case 'guide_passenger_list':
        (new GuideController())->passengerList();
        break;
    // ===========================
    // 7. QUẢN LÝ BOOKING (Đơn đặt tour)
    // ===========================
    case 'booking_list':
        (new BookingController())->index();
        break;
    case 'booking_add':
        (new BookingController())->add();
        break;
    case 'booking_detail':
        (new BookingController())->detail();
        break;

    // Booking cho tour thiết kế
    case 'booking_add_custom':
        (new BookingController())->addCustom();
        break;
    case 'custom_booking_list':
        (new BookingController())->customList();
        break;

    // ===========================
    // 8. DEFAULT (404)
    // ===========================
    default:
        echo "<h2>Lỗi 404: Trang không tồn tại!</h2>";
        break;
}

ob_end_flush();
