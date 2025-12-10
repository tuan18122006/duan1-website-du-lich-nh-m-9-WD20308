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

<div class="dashboard-guide container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Xin chào, <?= $_SESSION['user']['full_name'] ?? 'HDV' ?>! 👋</h4>
            <p class="text-muted mb-0">Chúc bạn một ngày làm việc tràn đầy năng lượng.</p>
        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-7">
            <?php if ($next_tour): ?>
                <div class="next-trip-card h-100 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="trip-badge"><i class="bi bi-clock me-1"></i> Sắp khởi hành</span>
                        <?php
                        $days_left = ceil((strtotime($next_tour['start_date']) - time()) / 86400);
                        ?>
                        <span class="fw-bold text-white fs-5">Còn <?= $days_left > 0 ? $days_left : 0 ?> ngày</span>
                    </div>

                    <h2 class="fw-bold mb-2"><?= htmlspecialchars($next_tour['tour_name']) ?></h2>

                    <div class="mt-3 fs-6 d-flex gap-4 flex-wrap">
                        <span><i class="bi bi-calendar-event me-2"></i><?= date('d/m/Y H:i', strtotime($next_tour['start_date'])) ?></span>
                        <span><i class="bi bi-people-fill me-2"></i><?= $next_tour['booked'] ?> / <?= $next_tour['stock'] ?> Khách</span>
                    </div>

                    <div class="mt-4">
                        <a href="index.php?act=guide_passenger_list&id=<?= $next_tour['tour_id'] ?>&schedule_id=<?= $next_tour['schedule_id'] ?>"
                            class="btn btn-light text-primary fw-bold px-4 rounded-pill shadow-sm">
                            <i class="bi bi-card-list me-2"></i> Xem danh sách khách
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card h-100 border-0 shadow-sm d-flex justify-content-center align-items-center p-5 text-center bg-white" style="border-radius: 15px;">
                    <div class="text-muted">
                        <i class="bi bi-cup-hot fs-1 mb-3 d-block"></i>
                        <h5>Hiện chưa có lịch trình mới</h5>
                        <p class="small">Hãy nghỉ ngơi và sẵn sàng cho những chuyến đi sắp tới nhé!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-5">
            <div class="row g-3 h-100">
                <div class="col-12">
                    <div class="stat-box blue">
                        <div class="stat-icon bg-light-blue"><i class="bi bi-send"></i></div>
                        <div>
                            <div class="text-muted small fw-bold">TOUR SẮP TỚI</div>
                            <h3 class="mb-0 fw-bold text-dark"><?= $stats['upcoming_count'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="stat-box green">
                        <div class="stat-icon bg-light-green"><i class="bi bi-check-circle"></i></div>
                        <div>
                            <div class="text-muted small fw-bold">ĐÃ CHẠY</div>
                            <h4 class="mb-0 fw-bold text-dark"><?= $stats['tours_done'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-calendar-range me-2"></i>Kế hoạch sắp tới</h5>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tên Tour</th>
                        <th>Thời gian</th>
                        <th>Đoàn khách</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($upcoming_tours)): ?>
                        <?php foreach ($upcoming_tours as $t): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($t['tour_name']) ?></span>
                                </td>
                                <td>
                                    <div class="small fw-bold"><?= date('d/m/Y', strtotime($t['start_date'])) ?></div>
                                    <div class="small text-muted"><?= date('H:i', strtotime($t['start_date'])) ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-person me-1"></i> <?= $t['booked'] ?> / <?= $t['stock'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $days = ceil((strtotime($t['start_date']) - time()) / 86400);
                                    if ($days <= 1) echo '<span class="badge bg-danger">Khẩn cấp</span>';
                                    else if ($days <= 3) echo '<span class="badge bg-warning text-dark">Sắp đi</span>';
                                    else echo '<span class="badge bg-info text-dark">Lên kế hoạch</span>';
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Không có lịch trình nào khác.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>