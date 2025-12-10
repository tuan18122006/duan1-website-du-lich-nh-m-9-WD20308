<div class="container-fluid mt-4">
    <h3 class="fw-bold text-primary mb-4"><i class="fas fa-users-cog me-2"></i>Quản lý Nhân sự (HDV)</h3>
    
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
                <?php foreach($guides as $g): ?>
                    <tr>
                        <td class="ps-4 fw-bold">
                            <img src="assets/uploads/<?= $g['avatar'] ?>" class="rounded-circle me-2" width="40" height="40">
                            <?= htmlspecialchars($g['full_name']) ?>
                        </td>
                        <td><?= $g['phone'] ?><br><small class="text-muted"><?= $g['email'] ?></small></td>
                        <td><?= $g['experience_years'] ?> năm</td>
                        <td>
                            <?php if($g['work_status'] == 1): ?>
                                <span class="badge bg-success">Đang làm việc</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Nghỉ phép</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="guide_id" value="<?= $g['guide_id'] ?>">
                                <?php if($g['work_status'] == 1): ?>
                                    <input type="hidden" name="status" value="0">
                                    <button type="submit" name="update_status" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-bed me-1"></i> Cho nghỉ phép
                                    </button>
                                <?php else: ?>
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" name="update_status" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-briefcase me-1"></i> Gọi đi làm
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>