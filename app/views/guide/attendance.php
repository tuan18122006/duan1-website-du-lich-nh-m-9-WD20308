<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-0"><i class="fas fa-clipboard-check me-2"></i>Điểm danh Đoàn</h4>
            <div class="text-muted small mt-1">Tour: <strong><?= htmlspecialchars($tour['tour_name']) ?></strong></div>
        </div>
        <a href="index.php?act=my_tour" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body">
            <form method="POST">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="60">
                                    <input type="checkbox" id="checkAll" class="form-check-input" style="cursor: pointer;">
                                </th>
                                <th>Hành khách</th>
                                <th>Chi tiết</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($passengerList)): ?>
                                <?php foreach($passengerList as $p): ?>
                                <tr class="<?= $p['is_checked_in'] ? 'table-success' : '' ?>">
                                    <td class="text-center">
                                        <input type="checkbox" name="checked_passengers[]" 
                                               value="<?= $p['passenger_id'] ?>" 
                                               class="form-check-input check-item"
                                               style="width: 20px; height: 20px; cursor: pointer;"
                                               <?= $p['is_checked_in'] ? 'checked' : '' ?>>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($p['full_name']) ?></div>
                                        <small class="text-muted">
                                            <?= $p['gender'] ?> - <?= $p['age'] ?> tuổi
                                        </small>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="fas fa-phone-alt text-secondary me-1"></i> <?= $p['customer_phone'] ?>
                                        </div>
                                        <div class="small text-muted fst-italic">
                                            (Người đặt: <?= htmlspecialchars($p['booker_name']) ?>)
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($p['is_checked_in']): ?>
                                            <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i> Có mặt</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary rounded-pill opacity-50">Chưa đến</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">Chưa có hành khách nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-grid gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> LƯU DANH SÁCH ĐIỂM DANH
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script xử lý nút "Chọn tất cả"
    document.getElementById('checkAll').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.check-item');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>