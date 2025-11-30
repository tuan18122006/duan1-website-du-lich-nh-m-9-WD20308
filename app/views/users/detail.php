

<body>
    <div class="breadcrumb">
        <a href="index.php?act=listkh"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a> / Chi tiết thông tin
    </div>
    
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


            <a href="index.php?act=editkh&id=<?= $khachhang['user_id'] ?>" class="btn-edit-profile"><i class="fas fa-pen"></i> Chỉnh sửa</a>
        </div>
    </div>

</body>

