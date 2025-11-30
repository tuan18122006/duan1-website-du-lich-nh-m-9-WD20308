<body>
    <div class="header-action">
        <h2 class="header-title">Quản lý Hướng dẫn viên</h2>
        <a href="index.php?act=add_guide" class="btn-add"><i class="fas fa-plus"></i> Thêm HDV</a>
    </div>

    <div class="user-grid">
        <?php if (!empty($listGuides)): ?>
            <?php foreach ($listGuides as $g): ?>
                <div class="user-card">
                    <div class="card-header">
                        <span class="status-active"><i class="fas fa-check-circle"></i> Hoạt động</span>
                        <span class="role-badge" style="background: #e67e22; color: white;">Nhân viên</span>
                    </div>

                    <div class="card-body">
                        <div class="info-col">
                            <h3 class="user-name"><?= $g['full_name'] ?></h3>
                            <div class="info-row"><i class="fas fa-id-badge"></i> <span>Kinh nghiệm: <b><?= $g['experience_years'] ?> năm</b></span></div>
                            <div class="info-row"><i class="fas fa-language"></i> <span>Ngoại ngữ: <?= $g['languages'] ?></span></div>
                            <div class="info-row"><i class="fas fa-phone"></i> <span><?= $g['phone'] ?></span></div>
                        </div>

                        <div class="img-col">
                            <?php
                            $imgUrl = !empty($g['avatar']) && file_exists("assets/uploads/" . $g['avatar']) 
                                ? BASE_URL . "assets/uploads/" . $g['avatar'] 
                                : BASE_URL . "assets/uploads/default-avatar.png";
                            ?>
                            <img src="<?= $imgUrl ?>" class="avatar-circle" onerror="this.src='<?= BASE_URL ?>assets/uploads/default-avatar.png';">
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="index.php?act=detail_guide&id=<?= $g['user_id'] ?>" class="btn-card btn-detail"><i class="fas fa-eye"></i> Chi tiết</a>
                        <a href="index.php?act=edit_guide&id=<?= $g['user_id'] ?>" class="btn-card btn-warning"><i class="fas fa-pen"></i> Sửa</a>
                        <a href="index.php?act=delete_guide&id=<?= $g['user_id'] ?>" onclick="return confirm('Xóa HDV này sẽ xóa cả tài khoản đăng nhập?')" class="btn-card btn-danger"><i class="fas fa-times"></i> Xóa</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center w-100">Chưa có dữ liệu HDV.</p>
        <?php endif; ?>
    </div>
</body>