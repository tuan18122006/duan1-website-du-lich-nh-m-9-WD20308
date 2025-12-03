<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Chi tiết Đơn hàng #<?= $booking['id'] ?></h3>
        <a href="index.php?act=booking_list" class="btn btn-secondary">Quay lại danh sách</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin Booking</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Khách hàng:</strong>
                            <p class="fs-5"><?= htmlspecialchars($booking['customer_name']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong>
                            <p><?= htmlspecialchars($booking['customer_phone']) ?></p>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Tour đăng ký:</strong>
                            <h5 class="text-primary mt-1"><?= htmlspecialchars($booking['tour_name']) ?></h5>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Ngày khởi hành:</strong>
                            <p class="fw-bold"><?= date('d/m/Y', strtotime($booking['start_date'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Số lượng khách:</strong>
                            <p><?= $booking['people'] ?> người</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tổng tiền:</strong>
                            <p class="text-danger fs-4 fw-bold"><?= number_format($booking['total_price']) ?> VNĐ</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày đặt:</strong>
                            <p><?= date('H:i d/m/Y', strtotime($booking['created_at'])) ?></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Ghi chú của khách:</strong>
                        <p class="bg-light p-3 rounded fst-italic">
                            <?= !empty($booking['note']) ? nl2br(htmlspecialchars($booking['note'])) : 'Không có ghi chú' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-white fw-bold">Trạng thái đơn hàng</div>
                <div class="card-body text-center">
                    <?php 
                        $st = $booking['status'];
                        $alertClass = match($st) {
                            'Chờ xử lý' => 'alert-warning',
                            'Đã xác nhận' => 'alert-info',
                            'Hoàn thành' => 'alert-success',
                            'Đã hủy' => 'alert-danger',
                            default => 'alert-secondary'
                        };
                    ?>
                    <div class="alert <?= $alertClass ?> fw-bold mb-4">
                        <?= strtoupper($st) ?>
                    </div>

                    <div class="d-grid gap-2">
                        <?php if ($st != 'Hoàn thành' && $st != 'Đã hủy'): ?>
                            <form method="POST">
                                <input type="hidden" name="action" value="mark_completed">
                                <button type="submit" class="btn btn-success w-100 py-2 fw-bold" 
                                        onclick="return confirm('Xác nhận khách đã thanh toán xong? Đơn hàng sẽ chuyển sang Hoàn thành.')">
                                    <i class="fas fa-check-double me-2"></i> XÁC NHẬN ĐÃ GIAO DỊCH
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if ($st == 'Chờ xử lý'): ?>
                            <form method="POST">
                                <input type="hidden" name="action" value="confirm">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-check me-2"></i> Xác nhận giữ chỗ
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if ($st != 'Đã hủy' && $st != 'Hoàn thành'): ?>
                            <form method="POST" class="mt-2">
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit" class="btn btn-outline-danger w-100" 
                                        onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                    <i class="fas fa-times me-2"></i> Hủy bỏ đơn hàng
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>