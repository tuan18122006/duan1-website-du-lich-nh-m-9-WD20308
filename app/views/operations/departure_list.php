<div class="container-fluid mt-4">
    <h3 class="fw-bold text-info mb-4"><i class="fas fa-plane-departure me-2"></i>Quản lý Khởi hành & Phân bổ</h3>
    
    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Tour / Lịch trình</th>
                        <th>HDV Phụ trách</th>
                        <th>Điểm tập kết</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($schedules as $s): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><?= htmlspecialchars($s['tour_name']) ?></div>
                            <small class="text-primary fw-bold">
                                <i class="far fa-clock"></i> <?= date('d/m/Y H:i', strtotime($s['start_date'])) ?>
                            </small>
                        </td>
                        <td>
                            <?php if($s['guide_name']): ?>
                                <span class="badge bg-info text-dark"><i class="fas fa-user-tie"></i> <?= $s['guide_name'] ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Chưa phân công</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="schedule_id" value="<?= $s['schedule_id'] ?>">
                                <input type="text" name="meeting_point" class="form-control form-control-sm" 
                                       value="<?= htmlspecialchars($s['meeting_point']) ?>" style="width: 150px;">
                                <button type="submit" name="update_point" class="btn btn-sm btn-primary">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="index.php?act=tour_schedules&id=<?= $s['tour_id'] ?>" class="btn btn-sm btn-outline-secondary">
                                Chi tiết
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>