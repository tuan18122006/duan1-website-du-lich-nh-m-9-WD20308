<?php
session_start();
ob_start();

// ===============================================================
// 1. IMPORT CẤU HÌNH & HÀM HỖ TRỢ
// ===============================================================
require_once 'app/helpers/env.php';
require_once 'app/helpers/functions.php';

// ===============================================================
// 2. IMPORT CORE
// ===============================================================
require_once 'app/core/Model.php';
require_once 'app/core/Controller.php';

// ===============================================================
// 3. IMPORT MODELS
// ===============================================================
require_once 'app/models/UserModel.php';
require_once 'app/models/TourModel.php';
require_once 'app/models/GuideModel.php';
require_once 'app/models/LoginModel.php';
require_once 'app/models/BookingModel.php';

// ===============================================================
// 4. IMPORT CONTROLLERS
// ===============================================================
require_once 'app/controllers/UserController.php';
require_once 'app/controllers/TourController.php';
require_once 'app/controllers/DashboardController.php';
require_once 'app/controllers/GuideController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/BookingController.php';
require_once 'app/controllers/OperationController.php';
require_once 'app/controllers/WelcomeController.php';

// ===============================================================
// 5. XỬ LÝ ĐIỀU HƯỚNG CƠ BẢN
// ===============================================================
$act = $_GET['act'] ?? 'welcome';

// Nếu chưa đăng nhập và không phải đang thực hiện login -> về trang welcome
if (!isset($_SESSION['user']) && $act != 'login' && $act != 'login_action') {
    $act = 'welcome';
} 
// Nếu đã đăng nhập mà vẫn vào trang welcome -> đẩy về trang chủ tương ứng theo quyền
elseif (isset($_SESSION['user']) && $act == 'welcome') {
    $act = ($_SESSION['user']['role'] == 2) ? 'guide_home' : 'dashboard';
}

// ===============================================================
// 6. ROUTING (ĐIỀU HƯỚNG CHI TIẾT)
// ===============================================================
switch ($act) {
    // --- TRANG CHÀO MỪNG ---
    case 'welcome': 
        (new WelcomeController())->index(); 
        break;
    
    // --- AUTHENTICATION ---
    case 'login': 
        (new LoginController())->index(); 
        break;
    case 'login_action': 
        (new LoginController())->login(); 
        break;
    case 'logout': 
        (new LoginController())->logout(); 
        break;

    // --- DASHBOARD (ADMIN) ---
    case 'home':
    case 'dashboard':
        (new DashboardController())->showDashboardCategory();
        break;

    // --- QUẢN LÝ KHÁCH HÀNG (USER) ---
    case 'listkh':      (new UserController())->index(); break;
    case 'addkh':       (new UserController())->create(); break;
    case 'storekh':     (new UserController())->store(); break;
    case 'editkh':      (new UserController())->edit(); break;
    case 'updatekh':    (new UserController())->update(); break;
    case 'deletekh':    (new UserController())->delete(); break;
    case 'detailkh':    (new UserController())->detail(); break;

    // --- QUẢN LÝ TOUR (ADMIN) ---
    // Tour Chuẩn
    case 'tour_list':           (new TourController())->showTour(); break;
    case 'add_tour':            (new TourController())->addTour(); break;
    case 'update_tour':         (new TourController())->updateTour(); break;
    case 'delete_tour':         (new TourController())->deleteTour(); break;
    case 'detail_tour':         (new TourController())->detailTour(); break;
    case 'tour_passenger_list': (new TourController())->passengerList(); break;
    case 'passenger_list':      (new TourController())->passengerList(); break;

    // Tour Thiết kế (Custom)
    case 'custom_tour_list':    (new TourController())->showCustomTours(); break;
    case 'tour_quote':          (new TourController())->quoteTour(); break;

    // Vận hành Tour
    case 'tour_schedules':      (new TourController())->manageSchedules(); break;
    case 'tour_bookings':       (new TourController())->tourBookings(); break;

    // --- QUẢN LÝ HƯỚNG DẪN VIÊN (ADMIN QUẢN LÝ) ---
    case 'list_guide':      (new GuideController())->index(); break;
    case 'add_guide':       (new GuideController())->create(); break;
    case 'store_guide':     (new GuideController())->store(); break;
    case 'edit_guide':      (new GuideController())->edit(); break;
    case 'update_guide':    (new GuideController())->update(); break;
    case 'detail_guide':    (new GuideController())->detail(); break;
    case 'delete_guide':    (new GuideController())->delete(); break;

    // ===================================================
    // GIAO DIỆN DÀNH RIÊNG CHO HDV (GUIDE PORTAL)
    // ===================================================
    case 'guide_home':
        (new GuideController())->dashboard();
        break;

    case 'my_tour':
        (new GuideController())->myTour();
        break;

    case 'checkin': // Xử lý Form điểm danh
        (new GuideController())->checkin();
        break;

    case 'checkin_history':
        (new GuideController())->checkinHistory();
        break;

    case 'guide_passenger_list':
        (new GuideController())->passengerList();
        break;

    // --- QUẢN LÝ BOOKING (ĐƠN ĐẶT TOUR) ---
    case 'booking_list':        (new BookingController())->index(); break;
    case 'booking_add':         (new BookingController())->add(); break;
    case 'booking_detail':      (new BookingController())->detail(); break;
    
    // Booking Custom
    case 'booking_add_custom':  (new BookingController())->addCustom(); break;
    case 'custom_booking_list': (new BookingController())->customList(); break;

    // --- VẬN HÀNH (OPERATION) ---
    case 'hr_management':        (new OperationController())->hrManagement(); break;
    case 'departure_management': (new OperationController())->departureManagement(); break;
    case 'tour_detail_checkin':  (new OperationController())->detailGroup(); break;

    // --- DEFAULT (404) ---
    default:
        // Nếu không tìm thấy trang, tự động điều hướng về trang chủ phù hợp
        if(isset($_SESSION['user'])) {
            if($_SESSION['user']['role'] == 2) {
                (new GuideController())->dashboard();
            } else {
                (new DashboardController())->showDashboardCategory();
            }
        } else {
            // Chưa đăng nhập thì về welcome
             (new WelcomeController())->index();
        }
        break;
}

ob_end_flush();
?>