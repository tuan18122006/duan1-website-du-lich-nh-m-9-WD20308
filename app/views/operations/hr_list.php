<div class="container-fluid mt-4">
    <h3 class="fw-bold text-primary mb-4"><i class="fas fa-users-cog me-2"></i>Quản lý Nhân sự (HDV)</h3>
    <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <input type="hidden" name="act" value="hr_management">
                    
                    <div class="col-md-8">
                        <input type="text" name="keyword" class="form-control" 
                            placeholder="Nhập tên HDV hoặc số điện thoại..." 
                            value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Tìm
                        </button>
                    </div>
                    
                    <div class="col-md-2">
                        <a href="index.php?act=hr_management" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Họ tên HDV</th>
                        <th>Liên hệ</th>
                        <th>Kinh nghiệm</th>
                        <th>Trạng thái hiện tại</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($guides)): ?>
                        <?php foreach($guides as $g): ?>
                            <tr>
                                <td class="ps-4 fw-bold">
                                    <?php 
                                        $avatar = !empty($g['avatar']) ? "assets/uploads/" . $g['avatar'] : "assets/images/default-avatar.png"; 
                                    ?>
                                    <img src="<?= $avatar ?>" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                                    
                                    <a href="index.php?act=detail_guide&id=<?= $g['user_id'] ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($g['full_name']) ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $g['phone'] ?><br>
                                    <small class="text-muted"><?= $g['email'] ?></small>
                                </td>
                                <td><?= $g['experience_years'] ?> năm</td>
                                <td>
                                <?php 
                                    // SỬA LỖI: Dùng biến $g thay vì $guide
                                    $status = $g['work_status'] ?? 1; 
                                ?>

                                <?php if ($status == 1): ?>
                                    <span class="badge bg-success">Sẵn sàng</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nghỉ phép</span>
                                <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                    <a href="index.php?act=assigned_tours&id=<?= $g['user_id'] ?>" 
                                    class="btn btn-sm btn-outline-info" 
                                    title="Xem danh sách tour">
                                        <i class="fas fa-list-ul me-1"></i> Xem Tour
                                    </a>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="guide_id" value="<?= $g['guide_id'] ?>">
                                            <?php if($g['work_status'] == 1): ?>
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit" name="update_status" class="btn btn-sm btn-outline-danger" title="Cho nghỉ phép">
                                                    <i class="fas fa-bed"></i> Nghỉ
                                                </button>
                                            <?php else: ?>
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" name="update_status" class="btn btn-sm btn-outline-success" title="Gọi đi làm">
                                                    <i class="fas fa-briefcase"></i> Gọi
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Không tìm thấy hướng dẫn viên nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
            </table>
        </div>
    </div>
</div>