<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?? 'Trang Quản Lý' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <?php if (isset($page_css) && !empty($page_css)): ?>
        <link rel="stylesheet" href="<?= $page_css ?>">
    <?php endif; ?>
    <style>
        :root {
            --sidebar-bg: #0d6efd;
            --body-bg: #f3f4f6;
        }

        /* 1. Thiết lập chiều cao 100% cho toàn trang để chặn cuộn body */
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* Quan trọng: Ẩn thanh cuộn của trình duyệt */
            background-color: var(--body-bg);
            font-family: 'Segoe UI', sans-serif;
        }

        /* 2. Làm cho Grid System của Bootstrap cao full màn hình */
        .container-fluid, .row {
            height: 100%; /* Kéo dài row và container xuống hết màn hình */
        }

        /* 3. Cấu hình Sidebar (Cột bên trái) */
        .sidebar {
            background: var(--sidebar-bg);
            color: white;
            height: 100%;       /* Full chiều cao */
            overflow-y: auto;   /* Tự có thanh cuộn nếu menu dài */
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            z-index: 10;
        }

        .sidebar a { color: rgba(255, 255, 255, 0.85); text-decoration: none; }
        .sidebar .nav-link:hover { background: rgba(255, 255, 255, 0.15); color: #fff; }
        .sidebar .nav-link.active { background: rgba(255, 255, 255, 0.2); color: #fff; font-weight: 600; }

        /* 4. Cấu hình Main (Cột bên phải) */
        main.col-lg-10 {
            height: 100%;       /* Full chiều cao */
            display: flex;      /* Dùng Flex để chia Topbar và Content */
            flex-direction: column;
            padding: 0 !important; /* Reset padding của col để Topbar full viền */
            background: var(--body-bg);
        }

        /* Topbar cố định ở trên */
        .top-header {
            background: #fff;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            flex-shrink: 0; /* Không bị co lại */
        }

        /* Vùng nội dung bên dưới (Cuộn độc lập) */
        .content-body-wrapper {
            flex-grow: 1;       /* Chiếm hết khoảng trống còn lại */
            overflow-y: auto;   /* CHỈ CUỘN VÙNG NÀY */
            padding: 1.5rem;
        }

        /* 5. Tùy chỉnh thanh cuộn (Scrollbar) cho đẹp hơn - giữ nguyên phần bạn thích */
        .sidebar::-webkit-scrollbar, 
        .content-body-wrapper::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb,
        .content-body-wrapper::-webkit-scrollbar-thumb { background-color: rgba(0, 0, 0, 0.2); border-radius: 4px; }
        .content-body-wrapper::-webkit-scrollbar-track { background: transparent; }

        /* Mobile */
        @media (max-width: 991.98px) {
            .sidebar { display: none; } /* Bootstrap d-none xử lý rồi nhưng thêm cho chắc */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row"> 
            
            <!-- Sidebar: Giữ nguyên class của bạn -->
            <nav class="col-lg-2 d-none d-lg-block sidebar p-3">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">QUẢN LÝ</h4>
                    <small>Dashboard</small>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a class="nav-link rounded px-3 py-2" href="index.php?act=dashboard"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded px-3 py-2" href="index.php?act=listkh"><i class="bi bi-people me-2"></i>Quản lí tài khoản</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded px-3 py-2" href="index.php?act=tour_list"><i class="bi bi-box-seam me-2"></i>Quản lí danh mục Tour</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded px-3 py-2" href="#orders"><i class="bi bi-receipt me-2"></i>Bán tuor và đặt chỗ</a></li>
                    <li class="nav-item mt-3">
                        <hr style="border-color: rgba(255,255,255,0.1)">
                    </li>
                </ul>
            </nav>

            <!-- Main Content Area: Giữ nguyên class của bạn -->
            <main class="col-lg-10 ms-auto">
                
                <!-- Topbar -->
                <div class="top-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">Menu</button>
                        <h3 class="mb-0 fs-4"><?= $page_title ?? 'Bảng điều khiển' ?></h3>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="input-group search-input d-none d-md-flex">
                            <input class="form-control" placeholder="Tìm kiếm..." aria-label="Tìm kiếm" />
                            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                        <div class="dropdown">
                            <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Admin</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="#">Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Content Wrapper: Phần này sẽ cuộn -->
                <div class="content-body-wrapper">
                    <?php
                    // Biến $view_path phải được Controller khai báo TRƯỚC khi gọi file này
                    if (isset($view_path)) {
                        require_once $view_path;
                    }
                    ?>
                </div>

            </main>
        </div>
    </div>

    <!-- Offcanvas Mobile Sidebar (Giữ nguyên logic cũ của bạn) -->
    <div class="offcanvas offcanvas-start bg-primary text-white" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">MENU QUẢN LÝ</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
             <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white active" href="#">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#users">Quản lí tài khoản</a></li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>