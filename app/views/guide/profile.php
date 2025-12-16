<?php
// [SỬA QUAN TRỌNG] Dùng trực tiếp biến $guide (được giải nén từ Header)
// Thay vì gọi $GLOBALS['view_data']['guide'] (đã bị xóa)
if (!isset($guide)) {
    $guide = [];
}

// Kiểm tra: Nếu mảng rỗng thì báo lỗi
if (empty($guide)) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>
            <strong>Lỗi:</strong> Không tải được thông tin hồ sơ.<br>
            Nguyên nhân: Có thể do phiên đăng nhập lỗi hoặc code Controller chưa truyền biến \$guide.<br>
            Vui lòng <a href='index.php?act=logout' class='fw-bold'>Đăng xuất</a> và thử lại.
          </div></div>";
    return;
}

// Xử lý đường dẫn ảnh
$avatarUrl = !empty($guide['avatar']) && file_exists("assets/uploads/" . $guide['avatar']) 
    ? "assets/uploads/" . $guide['avatar'] 
    : "assets/uploads/default-avatar.png";
?>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-5">
                    <div class="mb-3 position-relative d-inline-block">
                        <img src="<?= htmlspecialchars($avatarUrl) ?>" 
                             alt="Avatar" 
                             class="rounded-circle img-thumbnail shadow-sm object-fit-cover"
                             style="width: 150px; height: 150px;">
                        
                        <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-white border rounded-circle p-2 shadow-sm" style="cursor: pointer;">
                            <i class="bi bi-camera-fill text-primary"></i>
                        </label>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($guide['full_name'] ?? 'Chưa cập nhật tên') ?></h5>
                    <p class="text-muted mb-3">@<?= htmlspecialchars($guide['username'] ?? 'unknown') ?></p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                            <i class="bi bi-star-fill me-1"></i> <?= $guide['experience_years'] ?? 0 ?> năm kinh nghiệm
                        </span>
                    </div>

                    <div class="text-start px-3 mt-4">
                        <p class="mb-2"><i class="bi bi-envelope me-2 text-secondary"></i> <?= htmlspecialchars($guide['email'] ?? '') ?></p>
                        <p class="mb-2"><i class="bi bi-telephone me-2 text-secondary"></i> <?= htmlspecialchars($guide['phone'] ?? '') ?></p>
                        <p class="mb-0"><i class="bi bi-translate me-2 text-secondary"></i> <?= htmlspecialchars($guide['languages'] ?? '') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-gear me-2"></i>Cập nhật hồ sơ</h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?act=guide_profile" method="POST" enctype="multipart/form-data">
                        <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="previewImage(this)">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Họ và tên</label>
                                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($guide['full_name'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Ngày sinh</label>
                                <input type="date" name="birthday" class="form-control" 
                                       value="<?= !empty($guide['birthday']) ? date('Y-m-d', strtotime($guide['birthday'])) : '' ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($guide['email'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($guide['phone'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Kinh nghiệm (năm)</label>
                                <input type="number" name="experience_years" class="form-control" value="<?= htmlspecialchars($guide['experience_years'] ?? 0) ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Ngôn ngữ thành thạo</label>
                                <input type="text" name="languages" class="form-control" value="<?= htmlspecialchars($guide['languages'] ?? '') ?>">
                            </div>
                            
                            <div class="col-12 mt-4">
                                <div class="p-3 bg-light rounded border">
                                    <label class="form-label fw-bold text-danger mb-1"><i class="bi bi-lock"></i> Đổi mật khẩu</label>
                                    <div class="form-text mb-2">Chỉ nhập nếu bạn muốn thay đổi mật khẩu hiện tại.</div>
                                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới...">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-check-circle me-1"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.querySelector('.img-thumbnail').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>