<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trang Quản Lý - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #0d6efd; color: white; }
        .sidebar a { color: rgba(255,255,255,0.9); text-decoration: none; }
        .sidebar .nav-link.active { background: rgba(255,255,255,0.08); }
        .card-stats { border-radius: 12px; }
        /* Thêm class cho phần wrapper của nội dung động */
        .content-body-wrapper { padding-top: 20px; } 
        .table-wrap { background: white; padding: 1rem; border-radius: 12px; }
        .search-input { max-width: 360px; }
        @media (max-width: 991px) {
            .sidebar { position: relative; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (d-none d-lg-block nghĩa là chỉ hiển thị trên Desktop) -->
            <nav class="col-lg-2 d-none d-lg-block sidebar p-3">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">QUẢN LÝ</h4>
                    <small>Dashboard</small>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a class="nav-link active rounded px-3 py-2" href="#"><i class="bi bi-speedometer2 me-2"></i> Tổng quan</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded px-3 py-2" href="#users"><i class="bi bi-people me-2"></i> Người dùng</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded px-3 py-2" href="#products"><i class="bi bi-box-seam me-2"></i> Tour</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded px-3 py-2" href="#orders"><i class="bi bi-receipt me-2"></i> Đơn hàng</a></li>
                    <li class="nav-item mt-3">
                        <hr style="border-color: rgba(255,255,255,0.1)">
                        <small class="text-uppercase">Cài đặt</small>
                    </li>
                    <li class="nav-item mt-2"><a class="nav-link rounded px-3 py-2" href="#settings"><i class="bi bi-gear me-2"></i> Cấu hình</a></li>
                </ul>
            </nav>

            <!-- Main Content Area -->
            <main class="col-lg-10 ms-auto px-4">
                <!-- Topbar -->
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">Menu</button>
                        <!-- Tiêu đề trang động (nếu bạn muốn) -->
                        <h3 class="mb-0"><?= $page_title ?? 'Bảng điều khiển' ?></h3> 
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="input-group search-input">
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

    <!-- Mobile offcanvas sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Copy nội dung sidebar vào đây -->
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="#">Tổng quan</a></li>
                <li class="nav-item"><a class="nav-link" href="#users">Người dùng</a></li>
                <li class="nav-item"><a class="nav-link" href="#products">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="#orders">Đơn hàng</a></li>
                <li class="nav-item mt-3"><small class="text-uppercase">Cài đặt</small></li>
                <li class="nav-item mt-2"><a class="nav-link" href="#settings">Cấu hình</a></li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>