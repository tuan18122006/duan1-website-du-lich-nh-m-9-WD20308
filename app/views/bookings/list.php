<div class="container-fluid mt-4">
    <?php
        // Logic xác định tiêu đề Booking
        $current_type = $_GET['type'] ?? 0;
        $title_text = ($current_type == 1) ? "Quản lý Booking Tùy chọn (Yêu cầu thiết kế)" : "Quản lý Booking Mặc định";
    ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary fw-bold"><i class="bi bi-ticket-perforated me-2"></i><?= $title_text ?></h2>
            <p class="text-muted">Danh sách các đơn đặt tour từ khách hàng</p>
        </div>
        
        <?php if($current_type == 0): ?>
            <a href="index.php?act=booking_add" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i> Tạo Booking mới
            </a>
        <?php endif; ?>
        
        <?php if($current_type == 1): ?>
            <a href="index.php?act=booking_add_custom" class="btn btn-success shadow-sm">
                <i class="fas fa-paint-brush me-2"></i> Tạo Yêu cầu Thiết kế
            </a>
        <?php endif; ?>
    </div>
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
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-secondary">Danh sách đơn hàng</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th class="py-3">Khách hàng</th>
                            <th class="py-3">Tour / Ngày đi</th>
                            <th class="py-3 text-center">Vé</th>
                            <th class="py-3">Tổng tiền</th>
                            <th class="py-3">Trạng thái</th>
                            <th class="py-3 text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $item): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">#<?= $item['id'] ?></td>
                                
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($item['customer_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($item['customer_phone']) ?></small>
                                </td>

                                <td>
                                    <div class="fw-bold text-primary" style="font-size: 0.9rem;">
                                        <?= htmlspecialchars($item['tour_name'] ?? 'Tour đã xóa') ?>
                                    </div>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt"></i> 
                                        <?= isset($item['start_date']) ? date('d/m/Y', strtotime($item['start_date'])) : '---' ?>
                                    </small>
                                    <?php if($current_type == 1): ?>
                                        <br><span class="badge bg-light text-secondary border">Tour Custom</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-light text-dark border"><?= $item['people'] ?></span>
                                </td>

                                <td class="fw-bold text-danger">
                                    <?php 
                                        if($item['total_price'] == 0 && $current_type == 1) {
                                            echo '<span class="text-muted fst-italic">Đang báo giá</span>';
                                        } else {
                                            echo number_format($item['total_price']) . ' đ';
                                        }
                                    ?>
                                </td>

                                <td>
                                    <?php 
                                        $status = $item['status'] ?? 'Chờ xử lý';
                                        $badgeClass = match($status) {
                                            'Chờ xử lý' => 'bg-warning text-dark',
                                            'Đã xác nhận' => 'bg-info text-dark',
                                            'Hoàn thành' => 'bg-success',
                                            'Đã hủy' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                                </td>

                                <td class="text-end pe-4">
                                    <?php if ($status != 'Hoàn thành' && $status != 'Đã hủy'): ?>
                                        <form method="POST" action="index.php?act=booking_detail&id=<?= $item['id'] ?>" class="d-inline-block" onsubmit="return confirm('Xác nhận khách đã thanh toán xong? Trạng thái sẽ chuyển thành Hoàn thành.');">
                                            <input type="hidden" name="action" value="mark_completed">
                                            <button type="submit" class="btn btn-sm btn-success shadow-sm" title="Xác nhận đã giao dịch">
                                                <i class="fas fa-check-circle"></i> XN
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <a href="index.php?act=booking_detail&id=<?= $item['id'] ?>" 
                                    class="btn btn-outline-primary btn-sm ms-1 shadow-sm" 
                                    title="Xem chi tiết">
                                    <i class="fas fa-eye me-1"></i> <span class="fw-bold">Chi tiết</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-4">Chưa có dữ liệu.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>