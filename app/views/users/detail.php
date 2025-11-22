<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết hồ sơ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        .profile-card {
            display: flex;
            background: white;
            border-radius: 20px;
            /* Tăng độ bo tròn cho khung thẻ */
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: 50px auto;
        }

        .profile-sidebar {
            width: 35%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            text-align: center;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            /* Giữ ảnh tròn */
            border: 5px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .profile-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .profile-role {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            /* Bo tròn badge vai trò */
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
        }

        .profile-main {
            width: 65%;
            padding: 40px;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-item {
            display: flex;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            align-items: center;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 30px;
            color: #3498db;
            font-size: 18px;
        }

        .info-label {
            width: 120px;
            font-weight: 600;
            color: #555;
        }

        .info-value {
            flex: 1;
            color: #333;
            font-weight: 500;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 25px;
            background: #f4f6f9;
            color: #555;
            text-decoration: none;
            border-radius: 10px;
            /* Bo tròn nút quay lại */
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #e0e0e0;
        }

        .btn-edit-profile {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 25px;
            background: #f39c12;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            /* Bo tròn nút chỉnh sửa */
            font-weight: 600;
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <div class="profile-card">
        <!-- Sidebar trái -->
        <div class="profile-sidebar">
            <?php
            $imgUrl = "";
            $pathCheck = "assets/uploads/" . $khachhang['avatar'];
            if (!empty($khachhang['avatar']) && file_exists($pathCheck)) {
                $imgUrl = BASE_URL . "assets/uploads/" . $khachhang['avatar'];
            } else {
                $imgUrl = BASE_URL . "assets/uploads/default-avatar.png";
            }
            ?>
            <img src="<?= $imgUrl ?>" class="profile-img"
                onerror="this.onerror=null; this.src='<?= BASE_URL ?>assets/uploads/default-avatar.png';">

            <h2 class="profile-name"><?= $khachhang['full_name'] ?></h2>

            <span class="profile-role">
                <?php
                if ($khachhang['role'] == 1) echo 'Administrator';
                elseif ($khachhang['role'] == 2) echo 'Nhân viên';
                else echo 'Khách hàng'; // Role khác 1 và 2 (bao gồm 0) là Khách hàng
                ?>
            </span>
        </div>

        <!-- Nội dung phải -->
        <div class="profile-main">
            <h3 style="margin-top:0; color:#3498db; border-bottom: 2px solid #f4f6f9; padding-bottom: 10px;">Thông tin chi tiết</h3>

            <ul class="info-list">
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-user-tag"></i></span>
                    <span class="info-label">Username</span>
                    <span class="info-value"><?= $khachhang['username'] ?></span>
                </li>
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-envelope"></i></span>
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= $khachhang['email'] ?></span>
                </li>
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-phone-alt"></i></span>
                    <span class="info-label">Số điện thoại</span>
                    <span class="info-value"><?= $khachhang['phone'] ?></span>
                </li>
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-birthday-cake"></i></span>
                    <span class="info-label">Ngày sinh</span>
                    <span class="info-value">
                        <?= !empty($khachhang['birthday']) ? date('d/m/Y', strtotime($khachhang['birthday'])) : 'Chưa cập nhật' ?>
                    </span>
                </li>
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-fingerprint"></i></span>
                    <span class="info-label">Mã ID</span>
                    <span class="info-value">#<?= $khachhang['user_id'] ?></span>
                </li>
            </ul>

            <a href="index.php?act=listkh" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
            <a href="index.php?act=editkh&id=<?= $khachhang['user_id'] ?>" class="btn-edit-profile"><i class="fas fa-pen"></i> Chỉnh sửa</a>
        </div>
    </div>

</body>

</html>