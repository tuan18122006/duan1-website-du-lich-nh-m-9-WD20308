<?php
session_start();
ob_start();

// SỬA LẠI ĐƯỜNG DẪN (Bỏ ../ đi)
require_once 'app/helpers/env.php';      // Cũ: ../app/helpers/env.php
require_once 'app/helpers/functions.php';

require_once 'app/core/Model.php';
require_once 'app/core/Controller.php';

require_once 'app/models/UserModel.php';
// require_once 'app/models/TourModel.php';

require_once 'app/controllers/UserController.php';
// require_once 'app/controllers/TourController.php';


// 5. Lấy tham số act từ URL (mặc định là trang chủ)
$act = $_GET['act'] ?? '/';

// 6. Điều hướng bằng Switch (Dễ dùng hơn Match)
switch ($act) {

    // === TRANG CHỦ ===
    case '/':
    case 'home':
        echo "<h1>Đây là trang chủ</h1>"; // Bạn có thể gọi HomeController tại đây
        (new UserController())->index();
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

    // === MẶC ĐỊNH (404) ===
    default:
        echo "<h2>Lỗi 404: Trang không tồn tại!</h2>";
        // Hoặc include file 404 nếu bạn đã tạo:
        // include '../app/views/404.php';
        break;
}

ob_end_flush();
