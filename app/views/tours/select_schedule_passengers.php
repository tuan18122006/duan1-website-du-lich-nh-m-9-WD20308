<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary"><i class="fas fa-calendar-alt me-2"></i>Chọn Lịch Khởi Hành</h4>
            <p class="text-muted mb-0">Tour: <strong><?= htmlspecialchars($tour['tour_name']) ?></strong></p>
        </div>
        <a href="index.php?act=tour_list" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Ngày khởi hành</th>
                        <th>Ngày về</th>
                        <th>Số chỗ</th>
                        <th>Đã đặt</th>
                        <th class="text-end pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($schedules)): ?>
                        <?php foreach($schedules as $s): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-success">
                                <?= date('d/m/Y H:i', strtotime($s['start_date'])) ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($s['end_date'])) ?></td>
                            <td><?= $s['stock'] ?></td>
                            <td>
                                <span class="badge bg-info text-dark"><?= $s['booked'] ?> khách</span>
                            </td>
                            <td class="text-end pe-4">
                                <?php if($s['booked'] > 0): ?>
                                    <a href="index.php?act=tour_passenger_list&id=<?= $tour['tour_id'] ?>&schedule_id=<?= $s['schedule_id'] ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-list-ul me-1"></i> Xem DS Khách
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Trống</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4">Chưa có lịch trình nào được tạo.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>