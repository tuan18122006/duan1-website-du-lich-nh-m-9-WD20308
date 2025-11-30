<body>
    <div class="breadcrumb">
        <a href="index.php?act=list_guide"><i class="fas fa-arrow-left"></i> Danh sách HDV</a> / Chi tiết
    </div>
    
    <div class="profile-card">
        <div class="profile-sidebar">
            <?php
            $imgUrl = !empty($guide['avatar']) ? BASE_URL . "assets/uploads/" . $guide['avatar'] : BASE_URL . "assets/uploads/default-avatar.png";
            ?>
            <img src="<?= $imgUrl ?>" class="profile-img" onerror="this.src='<?= BASE_URL ?>assets/uploads/default-avatar.png';">
            <h2 class="profile-name"><?= $guide['full_name'] ?></h2>
            <span class="role-badge" style="background: #2980b9; color: white;">Hướng Dẫn Viên</span>
        </div>

        <div class="profile-main">
            <h3 class="section-title text-primary border-bottom pb-2 mb-3">Hồ sơ cá nhân</h3>
            <ul class="info-list">
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-user-circle"></i></span>
                    <span class="info-label">Username</span>
                    <span class="info-value"><?= $guide['username'] ?></span>
                </li>
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-envelope"></i></span>
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= $guide['email'] ?></span>
                </li>
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-phone"></i></span>
                    <span class="info-label">Điện thoại</span>
                    <span class="info-value"><?= $guide['phone'] ?></span>
                </li>
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-birthday-cake"></i></span>
                    <span class="info-label">Ngày sinh</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($guide['birthday'])) ?></span>
                </li>
            </ul>

            <h3 class="section-title text-warning border-bottom pb-2 mb-3 mt-4">Thông tin nghề nghiệp</h3>
            <ul class="info-list">
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-briefcase"></i></span>
                    <span class="info-label">Kinh nghiệm</span>
                    <span class="info-value fw-bold"><?= $guide['experience_years'] ?> năm</span>
                </li>
                <li class="info-item">
                    <span class="info-icon"><i class="fas fa-language"></i></span>
                    <span class="info-label">Ngoại ngữ</span>
                    <span class="info-value"><?= $guide['languages'] ?></span>
                </li>
            </ul>

            <div class="mt-4 text-end">
                <a href="index.php?act=edit_guide&id=<?= $guide['user_id'] ?>" class="btn-edit-profile"><i class="fas fa-pen"></i> Chỉnh sửa hồ sơ</a>
            </div>
        </div>
    </div>
</body>