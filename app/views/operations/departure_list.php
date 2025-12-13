<div class="container-fluid mt-4">
    <h3 class="fw-bold text-info mb-4"><i class="fas fa-plane-departure me-2"></i>Quản lý Khởi hành & Check-in</h3>
    <form method="GET" class="row g-3 mb-4 p-3 bg-white rounded shadow-sm">
    <input type="hidden" name="act" value="<?= $_GET['act'] ?>">
    
    <div class="col-md-5">
        <input type="text" name="keyword" class="form-control" 
               placeholder="Tìm theo tên tour, khách hàng, HDV..." 
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
    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Tour / Lịch trình</th>
                        <th>HDV & Điểm tập kết</th>
                        <th>Tiến độ Check-in</th> <th class="text-end pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($schedules as $s): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">
                                <?= htmlspecialchars($s['tour_name']) ?>
                            </div>
                            <div class="small text-primary fw-bold mt-1">
                                <i class="far fa-clock"></i> <?= date('d/m/Y H:i', strtotime($s['start_date'])) ?>
                            </div>
                            
                            <?php 
                                $badgeColor = match($s['tour_state']) {
                                    'Chờ khởi hành' => 'bg-warning text-dark',
                                    'Đang khởi hành' => 'bg-success',
                                    'Kết thúc'      => 'bg-secondary',
                                };
                            ?>
                            <span class="badge <?= $badgeColor ?> mt-1"><?= $s['tour_state'] ?></span>
                        </td>
                        
                        <td>
                            <div class="mb-2">
                                <?php if($s['guide_name']): ?>
                                    <span class="badge bg-info text-dark"><i class="fas fa-user-tie"></i> <?= $s['guide_name'] ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Chưa có HDV</span>
                                <?php endif; ?>
                            </div>
                            
                            <form method="POST" class="d-flex gap-1">
                                <input type="hidden" name="schedule_id" value="<?= $s['schedule_id'] ?>">
                                <input type="text" name="meeting_point" class="form-control form-control-sm" 
                                       value="<?= htmlspecialchars($s['meeting_point']) ?>" 
                                       placeholder="Điểm tập kết..." style="width: 140px;">
                                <button type="submit" name="update_point" class="btn btn-sm btn-light border" title="Lưu điểm tập kết">
                                    <i class="fas fa-save text-primary"></i>
                                </button>
                            </form>
                        </td>

                        <td style="width: 200px;">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Đã điểm danh:</span>
                                <span class="fw-bold"><?= $s['checkin_count'] ?>/<?= $s['total_guests'] ?></span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <?php 
                                    $percent = ($s['total_guests'] > 0) ? ($s['checkin_count'] / $s['total_guests']) * 100 : 0;
                                ?>
                                <div class="progress-bar bg-success" style="width: <?= $percent ?>%"></div>
                            </div>
                        </td>

                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="index.php?act=tour_detail_checkin&id=<?= $s['schedule_id'] ?>" 
                                   class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-list-alt me-1"></i> Chi tiết & Log
                                </a>
                                
                                <a href="index.php?act=tour_schedules&id=<?= $s['tour_id'] ?>" 
                                   class="btn btn-sm btn-outline-secondary" title="Sửa lịch">
                                    <i class="fas fa-cog"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>