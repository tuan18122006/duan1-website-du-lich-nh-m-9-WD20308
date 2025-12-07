<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Lịch khởi hành: <span class="text-primary"><?= htmlspecialchars($tour['tour_name']) ?></span></h3>
        <a href="index.php?act=tour_list" class="btn btn-secondary">Quay lại danh sách</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">Thêm ngày đi & Chọn HDV</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Ngày đi (*)</label>
                            <input type="datetime-local" name="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Ngày về (*)</label>
                            <input type="datetime-local" name="end_date" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Hướng dẫn viên</label>
                            <select name="guide_id" class="form-select">
                                <option value="">-- Chưa chỉ định --</option>
                                <?php if(!empty($guides)): ?>
                                    <?php foreach($guides as $g): ?>
                                        <option value="<?= $g['guide_id'] ?>">
                                            <?= htmlspecialchars($g['full_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Giá vé (VNĐ)</label>
                            <input type="number" name="price" class="form-control" value="<?= $tour['base_price'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Số chỗ tối đa</label>
                            <input type="number" name="stock" class="form-control" value="<?= $tour['people'] ?>" required>
                        </div>
                        <button type="submit" name="add_schedule" class="btn btn-success w-100">Thêm Lịch</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white fw-bold">Danh sách các chuyến đi sắp tới</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Thời gian</th>
                                <th>HDV Phụ trách</th> <th>Giá vé</th>
                                <th>Chỗ</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($schedules)): ?>
                                <?php foreach($schedules as $s): ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary"><?= date('d/m/Y H:i', strtotime($s['start_date'])) ?></span> <br>
                                        <small class="text-muted">đến <?= date('d/m/Y', strtotime($s['end_date'])) ?></small>
                                    </td>
                                    
                                    <td>
                                        <?php if(!empty($s['guide_name'])): ?>
                                            <span class="badge bg-info text-dark">
                                                <i class="fas fa-user-tie me-1"></i> <?= htmlspecialchars($s['guide_name']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small">Chưa có</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="fw-bold text-danger"><?= number_format($s['price']) ?></td>
                                    <td>
                                        <?= $s['booked'] ?> / <?= $s['stock'] ?>
                                    </td>
                                    <td>
                                        <a href="index.php?act=tour_schedules&id=<?= $tour['tour_id'] ?>&delete_id=<?= $s['schedule_id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Bạn có chắc muốn xóa lịch này?')">Xóa</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-4">Chưa có lịch nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>