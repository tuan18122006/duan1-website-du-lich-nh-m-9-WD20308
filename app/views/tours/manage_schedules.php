<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Lịch khởi hành: <span class="text-primary"><?= htmlspecialchars($tour['tour_name']) ?></span></h3>
        <a href="index.php?act=tour_list" class="btn btn-secondary">Quay lại danh sách</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <input type="hidden" id="tourDuration" value="<?= $tour['duration_days'] ?? 1 ?>">

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">Thêm ngày đi & Chọn HDV</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="fw-bold">Ngày đi (Khởi hành) (*)</label>
                            <input type="datetime-local" name="start_date" id="start_date" 
                                   class="form-control" required onchange="calculateEndDate()">
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold">Ngày về (Dự kiến):</label>
                            <input type="datetime-local" name="end_date" id="end_date" 
                                   class="form-control bg-light" readonly required>
                            <small class="text-muted fst-italic">Tự động cộng <?= $tour['duration_days'] ?? 1 ?> ngày theo Tour</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold">Hướng dẫn viên</label>
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
                            <label class="fw-bold">Giá vé (VNĐ)</label>
                            <input type="number" name="price" class="form-control" 
                                   value="<?= $tour['base_price'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Số chỗ tối đa</label>
                            <input type="number" name="stock" class="form-control" 
                                   value="<?= $tour['people'] ?>" required>
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
                                <th>HDV Phụ trách</th> 
                                <th>Giá vé</th>
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

                                    <td class="fw-bold text-danger">
                                        <?= number_format($s['price'] ?? 0) ?> đ
                                    </td>
                                    
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

<script>
    function calculateEndDate() {
        const startDateInput = document.getElementById('start_date').value;
        const duration = parseInt(document.getElementById('tourDuration').value) || 1;
        
        if (startDateInput) {
            const startDate = new Date(startDateInput);
            const endDate = new Date(startDate.getTime() + (duration * 24 * 60 * 60 * 1000));
            
            const year = endDate.getFullYear();
            const month = String(endDate.getMonth() + 1).padStart(2, '0');
            const day = String(endDate.getDate()).padStart(2, '0');
            const hours = String(endDate.getHours()).padStart(2, '0');
            const minutes = String(endDate.getMinutes()).padStart(2, '0');
            
            const formattedEndDate = `${year}-${month}-${day}T${hours}:${minutes}`;
            document.getElementById('end_date').value = formattedEndDate;
        }
    }
</script>