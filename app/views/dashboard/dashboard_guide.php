<style>
    /* CSS RIÊNG CHO DASHBOARD HDV */
    .dashboard-guide {
        padding: 20px;
    }

    /* 1. NEXT TRIP CARD (Quan trọng nhất) */
    .next-trip-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
        position: relative;
        overflow: hidden;
    }

    .next-trip-card::after {
        content: '\f3c5';
        /* Icon map marker */
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -30px;
        font-size: 150px;
        opacity: 0.1;
        color: white;
    }

    .trip-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        backdrop-filter: blur(5px);
    }

    /* 2. STATS MINI CARDS */
    .stat-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        height: 100%;
        border-left: 4px solid transparent;
    }

    .stat-box.blue {
        border-left-color: #0d6efd;
    }

    .stat-box.green {
        border-left-color: #198754;
    }

    .stat-box.orange {
        border-left-color: #fd7e14;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 15px;
    }

    .bg-light-blue {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .bg-light-green {
        background: #d1e7dd;
        color: #198754;
    }

    .bg-light-orange {
        background: #ffe5d0;
        color: #fd7e14;
    }

    /* 3. TABLE */
    .table-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }

    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #eee;
        color: #666;
        font-weight: 600;
        font-size: 0.9rem;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border">
        <div>
            <h4 class="fw-bold text-primary mb-1">Xin chào, <?= $_SESSION['user']['full_name'] ?? 'HDV' ?>! 👋</h4>
            <p class="text-muted mb-0">Chúc bạn một ngày làm việc hiệu quả và tràn đầy năng lượng.</p>
        </div>
        <div class="text-end d-none d-md-block">
            <h5 class="fw-bold mb-0"><?= date('d/m/Y') ?></h5>
            <small class="text-muted">Hôm nay</small>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <?php if ($next_tour): 
                $start = strtotime($next_tour['start_date']);
                $end = strtotime($next_tour['end_date']);
                $now = time();
                
                // Trạng thái
                if ($now >= $start && $now <= $end) {
                    $status_text = "Đang diễn ra";
                    $status_class = "bg-success";
                } elseif ($now < $start) {
                    $days = ceil(($start - $now)/86400);
                    $status_text = "Sắp khởi hành (Còn $days ngày)";
                    $status_class = "bg-warning text-dark";
                } else {
                    $status_text = "Vừa kết thúc";
                    $status_class = "bg-secondary";
                }
            ?>
                <div class="card border-0 shadow-sm h-100 overflow-hidden text-white" 
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); border-radius: 20px;">
                    <div class="card-body p-4 p-lg-5 d-flex flex-column justify-content-center position-relative">
                        <i class="bi bi-geo-alt-fill position-absolute" style="right: -20px; bottom: -30px; font-size: 10rem; opacity: 0.1;"></i>
                        
                        <div class="mb-3">
                            <span class="badge <?= $status_class ?> px-3 py-2 rounded-pill fs-6 mb-2">
                                <?= $status_text ?>
                            </span>
                        </div>

                        <h2 class="fw-bold mb-3"><?= htmlspecialchars($next_tour['tour_name']) ?></h2>

                        <div class="d-flex flex-wrap gap-4 mb-4 text-white-50">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-event fs-4 me-2 text-white"></i>
                                <div>
                                    <small class="d-block text-white">Khởi hành</small>
                                    <span class="fw-bold text-white"><?= date('H:i - d/m/Y', $start) ?></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-people-fill fs-4 me-2 text-white"></i>
                                <div>
                                    <small class="d-block text-white">Số lượng</small>
                                    <span class="fw-bold text-white"><?= $next_tour['booked'] ?> / <?= $next_tour['stock'] ?> Khách</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <a href="index.php?act=guide_passenger_list&id=<?= $next_tour['tour_id'] ?>&schedule_id=<?= $next_tour['schedule_id'] ?>" 
                               class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-card-list me-2"></i> Xem Danh sách & Điểm danh
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm h-100 bg-white d-flex align-items-center justify-content-center p-5 rounded-4">
                    <div class="text-center text-muted">
                        <i class="bi bi-cup-hot display-4 mb-3 d-block"></i>
                        <h5>Hiện không có lịch trình nào sắp tới</h5>
                        <p class="small">Hãy nghỉ ngơi nhé!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-5">
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="card border-0 shadow-sm bg-primary bg-opacity-10 h-100 rounded-4">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-send fs-3 text-primary mb-2"></i>
                            <h4 class="fw-bold text-dark mb-0"><?= $stats['upcoming_count'] ?? 0 ?></h4>
                            <small class="text-muted fw-bold">Tour sắp tới</small>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm bg-success bg-opacity-10 h-100 rounded-4">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-check-circle fs-3 text-success mb-2"></i>
                            <h4 class="fw-bold text-dark mb-0"><?= $stats['tours_done'] ?? 0 ?></h4>
                            <small class="text-muted fw-bold">Đã hoàn thành</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 fw-bold border-bottom">
                    <i class="bi bi-calendar-range me-2 text-primary"></i> Lịch trình khác
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush rounded-bottom-4">
                        <?php if (!empty($upcoming_tours)): ?>
                            <?php foreach ($upcoming_tours as $t): ?>
                                <div class="list-group-item px-3 py-3">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div style="width: 70%;">
                                            <h6 class="mb-1 text-truncate fw-bold"><?= htmlspecialchars($t['tour_name']) ?></h6>
                                            <small class="text-muted"><i class="bi bi-clock"></i> <?= date('d/m', strtotime($t['start_date'])) ?></small>
                                        </div>
                                        <span class="badge bg-light text-dark border"><?= $t['booked'] ?> khách</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted small">Không có lịch trình khác.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>