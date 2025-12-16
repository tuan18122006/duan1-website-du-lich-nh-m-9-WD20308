<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-0">
                <i class="fas fa-clipboard-list me-2"></i>Danh sách phân công
            </h4>
            <p class="text-muted mb-0 mt-1">
                HDV: <strong class="text-dark"><?= htmlspecialchars($guide['full_name']) ?></strong> 
                (Mã: #<?= $guide['guide_id'] ?>)
            </p>
        </div>
        <a href="index.php?act=hr_management" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th>Tên Tour</th>
                        <th>Lịch trình</th>
                        <th>Số khách & Điểm danh</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tours)): ?>
                        <?php foreach ($tours as $tour): 
                            // Xử lý trạng thái tour
                            $now = time();
                            $start = strtotime($tour['schedule_start']);
                            $end = strtotime($tour['schedule_end']);
                            
                            $status_label = '';
                            if ($now < $start) {
                                $status_label = '<span class="badge bg-warning text-dark">Sắp chạy</span>';
                            } elseif ($now >= $start && $now <= $end) {
                                $status_label = '<span class="badge bg-success">Đang đi</span>';
                            } else {
                                $status_label = '<span class="badge bg-secondary">Đã hoàn thành</span>';
                            }
                        ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#<?= $tour['tour_id'] ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                    <small class="text-muted">Mã lịch trình: <?= $tour['schedule_id'] ?></small>
                                </td>
                                <td>
                                    <div><i class="fas fa-calendar-check text-primary me-1"></i> Đi: <?= date('d/m/Y H:i', strtotime($tour['schedule_start'])) ?></div>
                                    <div class="small text-muted mt-1"><i class="fas fa-home me-1"></i> Về: <?= date('d/m/Y', strtotime($tour['schedule_end'])) ?></div>
                                </td>
                                <td>
                                    <?php 
                                        $booked = $tour['booked'] ?? 0;
                                        $stock = $tour['stock'] ?? 0;
                                        $checked_in = $tour['checked_in'] ?? 0; // Số người đã điểm danh
                                        
                                        // Tính % đặt chỗ
                                        $percent = ($stock > 0) ? round(($booked / $stock) * 100) : 0;
                                        $color = $percent >= 100 ? 'danger' : 'primary';
                                        
                                        // Tính % điểm danh (trên số khách đã đặt)
                                        $checkin_percent = ($booked > 0) ? round(($checked_in / $booked) * 100) : 0;
                                    ?>
                                    
                                    <div class="mb-1 d-flex justify-content-between small">
                                        <span class="text-muted">Đặt chỗ:</span>
                                        <span class="fw-bold"><?= $booked ?>/<?= $stock ?></span>
                                    </div>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-<?= $color ?>" style="width: <?= $percent ?>%"></div>
                                    </div>

                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Điểm danh:</span>
                                        <span class="fw-bold text-success"><?= $checked_in ?>/<?= $booked ?></span>
                                    </div>
                                </td>
                                <td><?= $status_label ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-calendar-times fs-1 mb-3 opacity-25"></i>
                                    <p>HDV này hiện chưa có lịch trình nào.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>