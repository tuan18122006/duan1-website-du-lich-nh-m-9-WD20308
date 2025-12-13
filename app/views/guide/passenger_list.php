<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">Danh sách Khách đoàn</h3>
            <p class="text-muted mb-0">
                Tour: <strong><?= htmlspecialchars($tour['tour_name'] ?? 'N/A') ?></strong> 
                (#<?= $schedule['schedule_id'] ?? 0 ?>)
            </p>
        </div>
        <a href="index.php?act=my_tour" class="btn btn-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <form action="index.php?act=checkin" method="POST">
        <input type="hidden" name="schedule_id" value="<?= $schedule['schedule_id'] ?? 0 ?>">
        
        <div class="card shadow border-0 rounded-4 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0"><i class="bi bi-check2-circle me-2"></i>Bảng Điểm Danh</h5>
                <div>
                    <span class="badge bg-light text-dark border me-2">Tổng: <?= count($passengers) ?> khách</span>
                </div>
            </div>
            
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4">Họ tên khách</th>
                            <th>Thông tin</th>
                            <th>Liên hệ</th>
                            <th class="text-center" width="150">Có mặt?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($passengers)): ?>
                            <?php foreach ($passengers as $p): ?>
                                <tr class="<?= $p['is_present'] ? 'table-success' : '' ?>">
                                    <td class="ps-4 fw-bold">
                                        <?= htmlspecialchars($p['full_name']) ?>
                                        <?php if($p['is_booker']): ?>
                                            <span class="badge bg-info text-dark ms-2" style="font-size: 0.7rem">Người đặt</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small text-muted"><?= $p['gender'] ?>, <?= $p['age'] ?> tuổi</div>
                                    </td>
                                    <td>
                                        <div class="small"><?= htmlspecialchars($p['customer_phone']) ?></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="passenger_ids[]" 
                                                   value="<?= $p['passenger_id'] ?? $p['id'] ?>" 
                                                   style="transform: scale(1.3); cursor: pointer;"
                                                   <?= $p['is_present'] ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">Chưa có khách trong danh sách.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm border-0 fixed-bottom m-3 m-md-4 rounded-4 bg-white" style="max-width: 600px; margin-left: auto !important;">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <label class="fw-bold small d-block mb-1">Ghi chú nhanh (Optional):</label>
                    <input type="text" name="note" class="form-control form-control-sm" placeholder="VD: Đón khách muộn 10p...">
                </div>
                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm ms-3">
                    <i class="bi bi-save me-2"></i> LƯU ĐIỂM DANH
                </button>
            </div>
        </div>
    </form>
</div>

<div style="height: 100px;"></div>