<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Booking #<?= $booking['id'] ?></h3>
        <a href="index.php?act=booking_list" class="btn btn-secondary btn-sm">Quay lại danh sách</a>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white fw-bold">Thông tin Khách hàng</div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> <?= htmlspecialchars($booking['customer_name']) ?></p>
                    <p><strong>SĐT:</strong> <?= htmlspecialchars($booking['customer_phone'] ?? '---') ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($booking['customer_email'] ?? '---') ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($booking['customer_address'] ?? '---') ?></p>
                    <p><strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($booking['note'] ?? '')) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white fw-bold">Thông tin Tour</div>
                <div class="card-body">
                    <p><strong>Tour:</strong> <?= htmlspecialchars($booking['tour_name']) ?></p>
                    <p><strong>Ngày đi:</strong> <?= !empty($booking['tour_start_date']) ? date('d/m/Y', strtotime($booking['tour_start_date'])) : '' ?></p>
                    <p><strong>Số người đi:</strong> <span class="badge bg-warning text-dark fs-6"><?= $booking['people'] ?></span></p>
                    <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold fs-5"><?= number_format($booking['total_price']) ?> VNĐ</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-top-primary shadow p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Trạng thái: 
                <?php 
                    $st = $booking['status'];
                    $cl = 'bg-secondary';
                    if($st=='Chờ xử lý') $cl='bg-warning text-dark';
                    if($st=='Đã xác nhận') $cl='bg-primary';
                    if($st=='Hoàn thành') $cl='bg-success';
                    if($st=='Đã hủy') $cl='bg-danger';
                ?>
                <span class="badge <?= $cl ?>"><?= $st ?></span>
            </h4>
        </div>

        <?php if($booking['status'] == 'Chờ xử lý'): ?>
            <div class="alert alert-warning">Đơn hàng mới! Vui lòng kiểm tra thông tin trước khi xác nhận.</div>
            <div>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="action" value="confirm">
                    <button class="btn btn-success fw-bold me-2"><i class="fas fa-check"></i> Xác nhận & Thanh toán</button>
                </form>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="action" value="cancel">
                    <button class="btn btn-outline-danger" onclick="return confirm('Bạn chắc chắn muốn hủy đơn này?')"><i class="fas fa-times"></i> Hủy đơn</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if($booking['status'] == 'Đã xác nhận'): ?>

            <div class="mt-3 text-end">
                <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#completeForm">
                     <i class="fas fa-flag-checkered"></i> Kết thúc Tour
                </button>
            </div>
        <?php endif; ?>

        <div class="collapse mt-3" id="completeForm">
            <div class="card card-body border-success">
                <form method="POST">
                    <input type="hidden" name="action" value="complete">
                    <h5 class="text-success">Đánh giá & Kết thúc Tour</h5>
                    <p class="small text-muted">Admin có thể nhập đánh giá thay cho khách hàng tại đây.</p>
                    <div class="form-group mb-3">
                        <label class="fw-bold">Nội dung đánh giá về HDV:</label>
                        <textarea name="feedback_content" class="form-control" rows="3" required placeholder="Nhập nhận xét..."></textarea>
                    </div>
                    <button class="btn btn-success fw-bold w-100">Xác nhận Hoàn thành</button>
                </form>
            </div>
        </div>
    </div>
</div>