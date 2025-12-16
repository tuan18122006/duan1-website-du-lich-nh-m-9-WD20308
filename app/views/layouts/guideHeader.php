<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?? 'HDV Dashboard' ?></title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --header-height: 70px;
            --sidebar-width: 240px;
            --primary-color: #0d6efd;
            --body-bg: #f1f5f9;
        }

        body {
            background: var(--body-bg);
            overflow-x: hidden;
        }

        /* =========================
           TOP HEADER HDV
        ========================== */
        .top-header {
            height: var(--header-height);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .btn-toggle-sidebar {
            border: none;
            background: transparent;
            font-size: 1.6rem;
            cursor: pointer;
            color: #334155;
        }


        /* =========================
           SIDEBAR HDV
        ========================== */
        .sidebar-hdv {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            height: 100vh;
            position: fixed;
            top: var(--header-height);
            left: 0;
            padding-top: 20px;
            overflow-y: auto;
            transition: 0.3s;
        }

        /* When sidebar collapsed */
        body.toggled .sidebar-hdv {
            margin-left: calc(var(--sidebar-width) * -1);
        }

        body.toggled .main-content {
            margin-left: 0;
        }

        /* Sidebar title */
        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            padding-left: 20px;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        /* Menu item */
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            margin: 5px 10px;
            color: #475569;
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.25s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #eff6ff;
            color: var(--primary-color);
        }

        .sidebar-menu i {
            font-size: 1.2rem;
            width: 22px;
        }

        @media (max-width: 991.98px) {
            .sidebar-hdv {
                margin-left: calc(var(--sidebar-width) * -1);
            }
        }



        .main-content {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: calc(100vh - var(--header-height));
            transition: 0.3s;
        }
    </style>
</head>

<body>

    <header class="top-header">
        <div class="d-flex align-items-center gap-3">
            <button class="btn-toggle-sidebar" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>

            <h4 class="fw-bold text-primary mb-0">
                <i class="fa-solid fa-compass me-2"></i>HDV Dashboard
            </h4>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <a class="btn btn-light border-0 d-flex align-items-center gap-2 rounded-pill px-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="fw-semibold d-none d-md-block">Xin chào, <?= $_SESSION['user']['name'] ?? 'HDV' ?></span>
                    
                    <?php
                        // Xử lý hiển thị Avatar
                        $userAvatar = $_SESSION['user']['avatar'] ?? '';
                        $avatarPath = "assets/uploads/" . $userAvatar;
                        
                        // Kiểm tra file có tồn tại không
                        if (!empty($userAvatar) && file_exists($avatarPath)) {
                            $displayAvatar = $avatarPath;
                        } else {
                            // Đường dẫn ảnh mặc định (đảm bảo bạn có file này)
                            $displayAvatar = "assets/uploads/default-avatar.png"; 
                        }
                    ?>
                    
                    <img src="<?= $displayAvatar ?>" 
                        alt="User Avatar" 
                        class="rounded-circle object-fit-cover border" 
                        style="width: 40px; height: 40px;">
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="index.php?act=guide_profile">
                            <i class="bi bi-person-circle me-2"></i> Hồ sơ cá nhân
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger d-flex align-items-center" href="index.php?act=logout">
                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <aside class="sidebar-hdv">
        <div class="sidebar-title">Menu HDV</div>

        <?php $act = $_GET['act'] ?? 'home'; ?>

        <div class="sidebar-menu">
            <a href="index.php?act=guide_home" class="<?= $act == 'guide_home' ? 'active' : '' ?>">
                <i class="bi bi-house-door"></i> Trang chủ
            </a>

            <a href="index.php?act=my_tour" class="<?= $act == 'my_tours' ? 'active' : '' ?>">
                <i class="bi bi-geo-alt"></i> Tour của tôi
            </a>

            <a href="index.php?act=checkin_history" class="<?= $act == 'checkinHistory' ? 'active' : '' ?>">
                <i class="bi bi-journal-text"></i> Nhật ký Công việc
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="content-body-wrapper">
            <?php

            if (isset($GLOBALS['view_data']) && is_array($GLOBALS['view_data'])) {
                extract($GLOBALS['view_data']);

                unset($GLOBALS['view_data']); // Dọn dẹp
            }

            $final_path = "";

            if (isset($GLOBALS['view_path'])) {
                $final_path = $GLOBALS['view_path'];
            } elseif (isset($view_path)) {
                $final_path = $view_path;
            }

            if (!empty($final_path) && file_exists($final_path)) {
                require_once $final_path;
            }
            ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.body.classList.toggle('toggled');
        });
    </script>

</body>

</html>