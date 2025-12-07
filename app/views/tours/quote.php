<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-success"><i class="fas fa-calculator me-2"></i>Báo giá & Chốt Tour</h3>
        <a href="index.php?act=custom_tour_list" class="btn btn-secondary">Quay lại</a>
    </div>

    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-light fw-bold">1. Yêu cầu từ khách hàng</div>
                    <div class="card-body bg-light">
                        <div class="mb-3">
                            <label class="fw-bold text-secondary">Tên chuyến đi:</label>
                            <p class="fs-5 fw-bold mb-1"><?= htmlspecialchars($tour['tour_name']) ?></p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-secondary">Ngày đi:</label>
                                <p><?= date('d/m/Y', strtotime($tour['start_date'])) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-secondary">Thời gian:</label>
                                <p><?= $tour['duration_days'] ?> ngày</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold text-secondary">Số lượng khách:</label>
                            <p class="fs-5"><?= $tour['people'] ?> người</p>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold text-secondary">Mô tả / Ghi chú của khách:</label>
                            <div class="p-3 bg-white rounded border">
                                <?= !empty($tour['description']) ? nl2br(htmlspecialchars($tour['description'])) : 'Không có mô tả' ?>
                            </div>
                        </div>

                        <?php if(!empty($tour['image_url'])): ?>
                            <div class="mb-3">
                                <label class="fw-bold text-secondary">Ảnh tham khảo:</label><br>
                                <img src="assets/uploads/tours/<?= $tour['image_url'] ?>" class="img-fluid rounded border mt-2" style="max-height: 150px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow border-0 border-top border-4 border-success">
                    <div class="card-header bg-white fw-bold text-success">2. Thiết lập Báo giá</div>
                    <div class="card-body">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Chi phí trọn gói (VNĐ/người) (*)</label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control form-control-lg border-success fw-bold text-danger" 
                                       value="<?= $tour['base_price'] > 0 ? $tour['base_price'] : '' ?>" 
                                       required placeholder="Nhập giá chốt...">
                                <span class="input-group-text bg-success text-white fw-bold">VNĐ</span>
                            </div>
                            <small class="text-muted">Giá này sẽ tự động cập nhật vào Booking của khách.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Phân công Hướng dẫn viên</label>
                            <select name="guide_id" class="form-select">
                                <option value="">-- Chưa chỉ định --</option>
                                <?php foreach($guides as $g): ?>
                                    <option value="<?= $g['guide_id'] ?>" <?= ($tour['guide_id'] == $g['guide_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($g['full_name']) ?> - <?= $g['phone'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Ghi chú báo giá / Lịch trình chi tiết</label>
                            <textarea name="policy" class="form-control" rows="5" placeholder="Nhập chi tiết bao gồm những gì..."><?= htmlspecialchars($tour['policy'] ?? '') ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg fw-bold" onclick="return confirm('Xác nhận báo giá? Hệ thống sẽ cập nhật đơn hàng của khách.')">
                                <i class="fas fa-check-circle me-2"></i> XÁC NHẬN & CẬP NHẬT ĐƠN
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>