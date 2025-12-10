<div class="container-fluid mt-4">
    <h3 class="fw-bold text-success mb-4"><i class="fas fa-clipboard-check me-2"></i>Quản lý Đoàn & Check-in</h3>
    
    <div class="row g-4">
        <?php foreach($schedules as $s): ?>
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="badge bg-light text-dark border">
                        <?= date('d/m/Y', strtotime($s['start_date'])) ?>
                    </span>
                    <?php 
                        $statusClass = match($s['tour_state']) {
                            'Chờ khởi hành' => 'bg-warning text-dark',
                            'Đang khởi hành' => 'bg-success',
                            'Kết thúc' => 'bg-secondary',
                        };
                    ?>
                    <span class="badge <?= $statusClass ?>"><?= $s['tour_state'] ?></span>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold text-truncate"><?= htmlspecialchars($s['tour_name']) ?></h5>
                    <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt me-1"></i> Tập kết: <?= $s['meeting_point'] ?></p>
                    
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold">Tiến độ điểm danh:</span>
                        <span class="small fw-bold text-primary">
                            <?= $s['checkin_count'] ?> / <?= $s['total_guests'] ?> khách
                        </span>
                    </div>

                    <div class="progress mb-3" style="height: 10px; border-radius: 5px;">
                        <?php 
                            // Tính phần trăm để vẽ thanh màu xanh
                            $percent = ($s['total_guests'] > 0) ? ($s['checkin_count'] / $s['total_guests']) * 100 : 0;
                        ?>
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                            role="progressbar" 
                            style="width: <?= $percent ?>%">
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button class="btn btn-light btn-sm text-primary">Xem chi tiết đoàn</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>