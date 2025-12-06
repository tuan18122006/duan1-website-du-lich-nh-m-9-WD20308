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


        /* =========================
           MAIN CONTENT
        ========================== */
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

    <!-- ======================================
         1. TOP HEADER
    ======================================= -->
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
            <span class="fw-semibold">Xin chào, <?= $_SESSION['user']['name'] ?? 'HDV' ?></span>

            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                 width="40" class="rounded-circle border">
        </div>
    </header>

    <!-- ======================================
         2. SIDEBAR HDV
    ======================================= -->
    <aside class="sidebar-hdv">
        <div class="sidebar-title">Menu HDV</div>

        <?php $act = $_GET['act'] ?? 'home'; ?>

        <div class="sidebar-menu">
            <a href="index.php?act=guide_home" class="<?= $act=='guide_home' ? 'active':'' ?>">
                <i class="bi bi-house-door"></i> Trang chủ
            </a>

            <a href="index.php?act=my_tour" class="<?= $act=='my_tours' ? 'active':'' ?>">
                <i class="bi bi-geo-alt"></i> Tour của tôi
            </a>

            <a href="index.php?act=my_booking" class="<?= $act=='my_booking' ? 'active':'' ?>">
                <i class="bi bi-journal-text"></i> Đơn Booking
            </a>

            <a href="index.php?act=calendar" class="<?= $act=='calendar' ? 'active':'' ?>">
                <i class="bi bi-calendar-week"></i> Lịch làm việc
            </a>
        </div>
    </aside>

    <!-- ======================================
         3. MAIN CONTENT
    ======================================= -->
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

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.body.classList.toggle('toggled');
        });
    </script>

</body>

</html>
