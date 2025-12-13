<div class="container-fluid px-4 mt-4">
    <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-geo-alt me-2"></i>Lịch Trình Của Tôi</h3>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= $_SESSION['success']; ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <form method="GET" class="row g-3 mb-4 p-3 bg-white rounded shadow-sm">
        <input type="hidden" name="act" value="<?= $_GET['act'] ?>">
        
        <div class="col-md-5">
            <input type="text" name="keyword" class="form-control" 
                placeholder="Tìm theo tên tour" 
                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" 
                value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Tìm</button>
        </div>
        <div class="col-md-2">
            <a href="index.php?act=<?= $_GET['act'] ?>" class="btn btn-outline-secondary w-100">Đặt lại</a>
        </div>
    </form>
    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Thông tin Tour</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tours)): ?>
                        <?php foreach ($tours as $tour):
                            $sid = $tour['schedule_id'];
                            $now = time();
                            $start = strtotime($tour['schedule_start']);
                            $end = strtotime($tour['schedule_end']);

                            // Logic trạng thái
                            if ($now < $start) $status = 'upcoming';
                            elseif ($now >= $start && $now <= $end) $status = 'ongoing';
                            else $status = 'completed';
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                    <small class="text-muted">Mã Tour: #<?= $tour['tour_id'] ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary"><?= date('d/m/Y H:i', $start) ?></div>
                                    <small class="text-muted">Đến: <?= date('d/m/Y', $end) ?></small>
                                </td>
                                <td>
                                    <?php if($status == 'upcoming'): ?>
                                        <span class="badge bg-warning text-dark">Sắp chạy</span>
                                    <?php elseif($status == 'ongoing'): ?>
                                        <span class="badge bg-success">Đang diễn ra</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Đã kết thúc</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="index.php?act=guide_passenger_list&id=<?= $tour['tour_id'] ?>&schedule_id=<?= $sid ?>" 
                                           class="btn btn-outline-primary btn-sm rounded-pill fw-bold">
                                            <i class="bi bi-people-fill me-1"></i> DS Khách & Điểm danh
                                        </a>

                                        <?php if ($status != 'upcoming'): ?>
                                            <button class="btn btn-warning btn-sm rounded-pill fw-bold" 
                                                    data-bs-toggle="modal" data-bs-target="#noteModal-<?= $sid ?>">
                                                <i class="bi bi-journal-text me-1"></i> Nhật ký
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <div class="modal fade text-start" id="noteModal-<?= $sid ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="index.php?act=checkin" method="POST" class="modal-content">
                                                <div class="modal-header bg-warning text-dark">
                                                    <h5 class="modal-title fw-bold">Ghi Nhật ký / Báo cáo</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="schedule_id" value="<?= $sid ?>">
                                                    <div class="mb-3">
                                                        <label class="fw-bold mb-2">Nội dung báo cáo:</label>
                                                        <textarea name="note" class="form-control" rows="5" placeholder="Ghi lại các sự việc phát sinh, chi tiêu hoặc đánh giá tour..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-dark">Lưu Nhật ký</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">Bạn chưa có lịch trình nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>