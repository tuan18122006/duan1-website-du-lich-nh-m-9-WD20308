<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?? 'Trang Quản Lý' ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <?php if (isset($page_css) && !empty($page_css)): ?>
        <link rel="stylesheet" href="<?= $page_css ?>">
    <?php endif; ?>

    <style>
        :root {
            --header-height: 70px;
            --sidebar-width: 300px;
            --sidebar-bg: #ffffff;
            --body-bg: #f1f5f9;
            --primary-color: #0d6efd;
            --transition-speed: 0.5s;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden; /* Tránh thanh cuộn ngang khi animation */
        }

        /* 1. TOP HEADER (Cố định trên cùng) */
        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            z-index: 1030;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: all var(--transition-speed);
        }

        /* 2. SIDEBAR (Cố định bên trái, dưới header) */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            overflow-y: auto;
            z-index: 1020;
            transition: all var(--transition-speed);
            padding-top: 1rem;
        }

        /* Style cho Link Sidebar */
        .sidebar .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: 0.8rem 1.2rem;
            margin: 4px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #eff6ff;
            color: var(--primary-color);
        }
        .sidebar .nav-link i { width: 24px; font-size: 1.1rem; }

        /* 3. MAIN CONTENT (Đẩy sang phải để nhường chỗ Sidebar) */
        .main-content {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            min-height: calc(100vh - var(--header-height));
            padding: 0;
            transition: all var(--transition-speed);
            display: flex;
            flex-direction: column;
        }

        .content-body-wrapper {
            padding: 1.5rem;
            flex-grow: 1;
        }

        /* --- LOGIC ĐÓNG/MỞ SIDEBAR (TOGGLE) --- */
        
        /* Khi body có class 'toggled' -> Sidebar ẩn sang trái */
        body.toggled .sidebar {
            margin-left: calc(var(--sidebar-width) * -1);
        }
        /* Khi body có class 'toggled' -> Main content tràn ra full màn hình */
        body.toggled .main-content {
            margin-left: 0;
        }

        /* Nút 3 gạch */
        .btn-toggle-sidebar {
            border: none;
            background: transparent;
            font-size: 1.5rem;
            cursor: pointer;
            color: #334155;
            padding: 5px;
            margin-right: 10px;
        }
        /* --- CSS CHO MOBILE SIDEBAR (OFFCANVAS) --- */
    
        #mobileSidebar {
            background-color: #ffffff; /* Nền trắng */
        }

        #mobileSidebar .offcanvas-header {
            background-color: #f8f9fa; /* Header màu xám nhẹ */
            height: var(--header-height); /* Cao bằng header chính */
            align-items: center;
        }

        /* Style cho từng mục menu mobile */
        #mobileSidebar .nav-link {
            color: #4b5563; /* Màu chữ xám đậm */
            font-size: 1rem; /* Chữ to dễ bấm */
            font-weight: 500;
            padding: 15px 20px; /* Khoảng cách rộng để dễ chạm ngón tay */
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f3f4f6; /* Đường kẻ mờ ngăn cách */
            transition: all 0.2s ease;
        }

        /* Icon bên trái */
        #mobileSidebar .nav-link i {
            font-size: 1.2rem;
            width: 30px;
            color: #9ca3af;
            transition: color 0.2s;
        }

        /* Hiệu ứng khi bấm/giữ vào menu */
        #mobileSidebar .nav-link:hover, 
        #mobileSidebar .nav-link:active {
            background-color: #eff6ff; /* Nền xanh nhạt */
            color: var(--primary-color); /* Chữ xanh đậm */
            padding-left: 25px; /* Hiệu ứng trượt nhẹ sang phải */
        }

        #mobileSidebar .nav-link:hover i {
            color: var(--primary-color);
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .sidebar { margin-left: calc(var(--sidebar-width) * -1); } /* Mặc định ẩn trên mobile */
            .main-content { margin-left: 0; }
            /* Logic mobile sẽ dùng Offcanvas của Bootstrap bên dưới */
        }
    </style>
</head>

<body>

    <!-- =============================================
         1. TOP HEADER (MỚI)
         ============================================= -->
    <header class="top-header">
        <div class="d-flex align-items-center">
            <!-- Nút Toggle Sidebar cho Desktop -->
            <button class="btn btn-light border-0 me-3 btn-toggle-sidebar" id="sidebarToggle">
                <i class="fa-solid fa-bars fs-5"></i>
            </button>

            <!-- Nút Toggle cho Mobile -->
            <button class="btn btn-outline-secondary d-lg-none me-2" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <h4 class="mb-0 fw-bold text-primary" style="font-size: 1.25rem;">
                <i class="fa-solid fa-layer-group me-2"></i>TRANG QUẢN LÝ
            </h4>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- Ô tìm kiếm -->
            <div class="input-group search-input d-none d-md-flex" style="width: 300px;">
                <input class="form-control rounded-start-pill bg-light border-end-0" placeholder="Tìm kiếm..." />
                <button class="btn btn-light border border-start-0 rounded-end-pill"><i class="bi bi-search"></i></button>
            </div>

            <!-- Avatar Admin -->
            <div class="dropdown">
                <a class="btn btn-light border-0 d-flex align-items-center gap-2 rounded-pill px-3" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-circle-user fs-4 text-secondary"></i>
                    <span class="d-none d-sm-block small fw-bold text-dark">Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><a class="dropdown-item" href="#">Hồ sơ</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="index.php?act=logout">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- =============================================
         2. SIDEBAR (CÓ LOGIC PHP)
         ============================================= -->
    <nav class="sidebar">
        <?php
        // LOGIC PHP: Giữ trạng thái menu khi reload trang
        $act = $_GET['act'] ?? 'dashboard';
        $userActs = ['listkh', 'addkh', 'editkh', 'detailkh', 'list_guide', 'add_guide', 'edit_guide', 'detail_guide'];
        
        $showUserMenu = in_array($act, $userActs) ? 'show' : '';
        $activeUserParent = in_array($act, $userActs) ? 'active' : 'collapsed';
        $ariaExpanded = in_array($act, $userActs) ? 'true' : 'false';
        ?>


        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?= $act == 'dashboard' ? 'active' : '' ?>" href="index.php?act=dashboard">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <!-- Quản lý tài khoản (Đa cấp) -->
            <li class="nav-item">
                <a class="nav-link <?= $activeUserParent ?>" href="#subMenuUser" data-bs-toggle="collapse" aria-expanded="<?= $ariaExpanded ?>">
                    <i class="bi bi-people me-2"></i> Quản lý tài khoản 
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                </a>
                <div class="collapse <?= $showUserMenu ?>" id="subMenuUser">
                    <ul class="nav flex-column ps-4">
                        <li class="nav-item">
                            <a class="nav-link <?= ($act == 'listkh' || $act == 'addkh') ? 'fw-bold text-primary' : '' ?>" href="index.php?act=listkh">
                                <i class="fas fa-user me-2"></i> Người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($act == 'list_guide' || $act == 'add_guide') ? 'fw-bold text-primary' : '' ?>" href="index.php?act=list_guide">
                                <i class="fas fa-id-card me-2"></i> Hướng dẫn viên
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Các mục khác -->
            <li class="nav-item">
                <a class="nav-link <?= strpos($act, 'tour') !== false ? 'active' : '' ?>" href="index.php?act=tour_list">
                    <i class="bi bi-box-seam me-2"></i> Quản lí Tour
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=booking_list">
                    <i class="bi bi-receipt me-2"></i> Quản lí Booking
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=tour_history">
                    <i class="fas fa-history"></i> Lịch sử Tour
                </a>
            </li>
            <li class="nav-item mt-3 px-3">
                <hr class="text-secondary opacity-25">
            </li>

        </ul>
    </nav>

    <!-- =============================================
         3. MAIN CONTENT
         ============================================= -->
    <main class="main-content">
        <div class="content-body-wrapper">
            <?php
            // Render View Động từ Controller
            if (isset($view_path) && file_exists($view_path)) {
                require_once $view_path;
            } else {
                echo '<div class="alert alert-info shadow-sm border-0">
                        <i class="bi bi-info-circle-fill me-2"></i> 
                        Nội dung trang web sẽ hiển thị tại đây.
                      </div>';
            }
            ?>
        </div>
    </main>

    <!-- =============================================
         4. MOBILE SIDEBAR (OFFCANVAS)
         ============================================= -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-primary">
                <i class="fa-solid fa-bars me-2"></i>MENU QUẢN LÝ
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        
        <div class="offcanvas-body p-0">
            <ul class="nav flex-column py-2">
                <li class="nav-item"><a class="nav-link" href="index.php?act=dashboard"><i class="bi bi-speedometer2 me-3"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?act=listkh"><i class="bi bi-people me-3"></i>Người dùng</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?act=list_guide"><i class="fas fa-id-card me-3"></i>Hướng dẫn viên</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?act=tour_list"><i class="bi bi-box-seam me-3"></i>Quản lí Tour</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?act=booking_list"><i class="bi bi-receipt me-3"></i>Quản lí Booking</a></li>
            </ul>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JS Toggle Sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const body = document.body;

            if(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    // Thêm/Xóa class 'toggled' vào body để kích hoạt CSS ẩn/hiện
                    body.classList.toggle('toggled');
                });
            }
        });
    </script>
</body>
</html>