<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Chi tiết Đoàn & Nhật ký HDV</h3>
        <a href="index.php?act=departure_management" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white fw-bold"><i class="fas fa-users me-2"></i>Tiến độ Điểm danh</div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Họ tên khách</th>
                                <th>Thông tin</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($passengers)): ?>
                            <?php foreach($passengers as $p): ?>
                                <tr>
                                    <td class="ps-3 fw-bold"><?= htmlspecialchars($p['full_name']) ?></td>
                                    <td><?= $p['gender'] ?>, <?= $p['age'] ?> tuổi</td>
                                    <td>
                                        <?php if($p['is_present']): ?>
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Có mặt</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Vắng / Chưa check</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-3">Chưa có khách.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow border-0 bg-light">
                <div class="card-header bg-warning text-dark fw-bold"><i class="fas fa-book me-2"></i>Nhật ký / Báo cáo từ HDV</div>
                <div class="card-body">
                    <?php if(!empty($logs)): ?>
                        <div class="timeline">
                            <?php foreach($logs as $log): ?>
                                <div class="border-start border-3 border-primary ps-3 mb-3">
                                    <p class="small text-muted mb-1">
                                        <?= date('d/m/Y H:i', strtotime($log['checkin_time'])) ?> - 
                                        <strong><?= htmlspecialchars($log['guide_name']) ?></strong>
                                    </p>
                                    <div class="bg-white p-2 rounded border">
                                        <?= nl2br(htmlspecialchars($log['note'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">HDV chưa ghi nhật ký nào.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>