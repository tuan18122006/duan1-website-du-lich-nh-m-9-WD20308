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

                    <span class="role-badge role-user">Người dùng</span>
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
