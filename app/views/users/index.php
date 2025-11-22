<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        /* --- Header --- */
        .header-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .header-title {
            color: #5a6b7c;
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .btn-add {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.2s;
        }

        .btn-add:hover {
            background-color: #2980b9;
        }

        /* --- Grid Layout --- */
        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        /* --- Card Design --- */
        .user-card {
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-top: 3px solid #d2d6de;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Header Card */
        .card-header {
            padding: 10px 15px;
            background-color: #fff;
            border-bottom: 1px solid #f4f4f4;
            color: #444;
            font-size: 13px;
            font-weight: 600;
        }

        .status-active {
            color: #00a65a;
        }

        /* Card Body */
        .card-body {
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .info-col {
            flex: 1;
            padding-right: 10px;
        }

        .user-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 0 0 10px 0;
        }

        .info-row {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .info-row i {
            width: 20px;
            color: #999;
        }

        .img-col {
            width: 90px;
            text-align: center;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #d2d6de;
            padding: 2px;
            background-color: #fff;
        }

        /* Footer chứa nút bấm */
        .card-footer {
            padding: 10px 15px;
            background-color: #f7f7f7;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            /* Căn phải */
            gap: 5px;
            /* Khoảng cách giữa các nút */
        }

        /* Style chung cho các nút trong Card (QUAN TRỌNG: Đổi tên class chung để tránh nhầm lẫn) */
        .btn-card {
            padding: 6px 12px;
            font-size: 12px;
            font-weight: bold;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.2s;
        }

        /* --- MÀU SẮC CỤ THỂ CHO TỪNG NÚT --- */

        /* Nút Chi tiết (Màu Xanh Dương Nhạt - Giống AdminLTE) */
        .btn-detail {
            background-color: #00c0ef;
        }

        .btn-detail:hover {
            background-color: #00acd6;
        }

        /* Nút Sửa (Màu Vàng Cam) */
        .btn-warning {
            background-color: #f39c12;
        }

        .btn-warning:hover {
            background-color: #e08e0b;
        }

        /* Nút Xóa (Màu Đỏ) */
        .btn-danger {
            background-color: #dd4b39;
        }

        .btn-danger:hover {
            background-color: #d73925;
        }

        /* Role badge */
        .role-badge {
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 5px;
        }

        .role-admin {
            background: #dd4b39;
            color: white;
        }

        .role-staff {
            background: #00c0ef;
            color: white;
        }

        .role-user {
            background: #d2d6de;
            color: #444;
        }
    </style>
</head>

<body>

    <div class="header-action">
        <h2 class="header-title">Quản lý người dùng</h2>
        <a href="index.php?act=addkh" class="btn-add"><i class="fas fa-plus"></i> Thêm mới</a>
    </div>

    <div class="user-grid">
        <?php if (!empty($listkhachhang)): ?>
            <?php foreach ($listkhachhang as $kh): ?>

                <div class="user-card">
                    <!-- Header -->
                    <div class="card-header">
                        <span class="status-active"><i class="fas fa-check-circle"></i> Đã kích hoạt</span>

                        <?php
                        if ($kh['role'] == 1) echo '<span class="role-badge role-admin">Admin</span>';
                        elseif ($kh['role'] == 2) echo '<span class="role-badge role-staff">Nhân viên</span>';
                        else echo '<span class="role-badge role-user">Khách hàng</span>';
                        ?>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <div class="info-col">
                            <h3 class="user-name"><?= $kh['full_name'] ?></h3>
                            <div class="info-row"><i class="fas fa-user"></i> <span>About: <b><?= $kh['username'] ?></b></span></div>
                            <div class="info-row"><i class="fas fa-envelope"></i> <span style="word-break: break-all;"><?= $kh['email'] ?></span></div>
                            <div class="info-row"><i class="fas fa-phone"></i> <span>Phone: <?= $kh['phone'] ?></span></div>
                        </div>

                        <div class="img-col">
                            <?php
                            $imgUrl = "";
                            $pathCheck = "assets/uploads/" . $kh['avatar'];
                            if (!empty($kh['avatar']) && file_exists($pathCheck)) {
                                $imgUrl = BASE_URL . "assets/uploads/" . $kh['avatar'];
                            } else {
                                $imgUrl = BASE_URL . "assets/uploads/default-avatar.png";
                            }
                            ?>
                            <a href="index.php?act=detailkh&id=<?= $kh['user_id'] ?>" title="Xem chi tiết">
                                <img src="<?= $imgUrl ?>" class="avatar-circle" alt="User Image"
                                    onerror="this.onerror=null; this.src='<?= BASE_URL ?>assets/uploads/default-avatar.png';">
                            </a>
                        </div>
                    </div>

                    <!-- Footer: CÁC NÚT BẤM ĐÃ ĐƯỢC STYLE -->
                    <div class="card-footer">
                        <!-- Nút Chi tiết (Xanh dương) -->
                        <a href="index.php?act=detailkh&id=<?= $kh['user_id'] ?>" class="btn-card btn-detail">
                            <i class="fas fa-eye"></i> Chi tiết
                        </a>

                        <!-- Nút Sửa (Vàng) -->
                        <a href="index.php?act=editkh&id=<?= $kh['user_id'] ?>" class="btn-card btn-warning">
                            <i class="fas fa-pen"></i> Sửa
                        </a>

                        <!-- Nút Xóa (Đỏ) -->
                        <a href="index.php?act=deletekh&id=<?= $kh['user_id'] ?>"
                            onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?')"
                            class="btn-card btn-danger">
                            <i class="fas fa-times"></i> Xóa
                        </a>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; width:100%;">Chưa có dữ liệu người dùng.</p>
        <?php endif; ?>
    </div>

</body>
<!-- HIỂN THỊ THÔNG BÁO -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" style="background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border:1px solid #c3e6cb; border-radius:4px;">
        <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:20px; border:1px solid #f5c6cb; border-radius:4px;">
        <i class="fas fa-exclamation-triangle"></i> <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

</html>