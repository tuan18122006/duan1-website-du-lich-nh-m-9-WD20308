<div class="container-fluid px-4 mt-4">
    <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-geo-alt me-2"></i>Lịch Trình Của Tôi</h3>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3">ID Tour</th>
                        <th>Tên Tour</th>
                        <th>Lịch trình</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if(!empty($tours)): ?>
                        <?php foreach ($tours as $tour): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#<?= $tour['tour_id'] ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                </td>
                                <td>
                                    <div><i class="bi bi-calendar-event me-1 text-info"></i> Đi: <?= date('d/m/Y H:i', strtotime($tour['schedule_start'])) ?></div>
                                    <div class="small text-muted mt-1"><i class="bi bi-calendar-check me-1"></i> Về: <?= date('d/m/Y', strtotime($tour['schedule_end'])) ?></div>
                                </td>
                                <td>
                                    <?php 
                                        $status = $tour['status'];
                                        // Kiểm tra ngày để hiện trạng thái chính xác hơn
                                        $now = time();
                                        $start = strtotime($tour['schedule_start']);
                                        $end = strtotime($tour['schedule_end']);

                                        if ($now < $start) {
                                            echo '<span class="badge bg-warning text-dark">Sắp khởi hành</span>';
                                        } elseif ($now >= $start && $now <= $end) {
                                            echo '<span class="badge bg-success">Đang diễn ra</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">Đã hoàn thành</span>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="index.php?act=guide_passenger_list&id=<?= $tour['tour_id'] ?>&schedule_id=<?= $tour['schedule_id'] ?>"  
                                       class="btn btn-primary btn-sm rounded-pill px-3">
                                       <i class="bi bi-people-fill me-1"></i> Xem khách
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Bạn chưa được phân công tour nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>