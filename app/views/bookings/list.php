
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary fw-bold"><i class="bi bi-ticket-perforated me-2"></i>Quản lý Đặt Tour</h2>
            <p class="text-muted">Danh sách các đơn đặt tour từ khách hàng</p>
        </div>
        <a href="index.php?act=booking_add" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i> Tạo Booking mới
        </a>
    </div>

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
                            <th class="py-3">Tour đăng ký</th>
                            <th class="py-3">Ngày đi</th>
                            <th class="py-3 text-center">Số người</th>
                            <th class="py-3">Tổng tiền</th>
                            <th class="py-3">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $item): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#<?= $item['id'] ?></td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-light text-primary me-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($item['customer_name'] ?? 'Khách vãng lai') ?></div>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone-alt me-1" style="font-size: 10px;"></i>
                                                    <?= htmlspecialchars($item['customer_phone'] ?? '---') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="max-width: 250px;">
                                        <div class="text-truncate fw-medium" title="<?= htmlspecialchars($item['tour_name'] ?? '') ?>">
                                            <?= htmlspecialchars($item['tour_name'] ?? 'Tour đã bị xóa') ?>
                                        </div>
                                    </td>

                                    <td>
                                        <?= isset($item['start_date']) ? date('d/m/Y', strtotime($item['start_date'])) : '---' ?>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border"><?= $item['people'] ?> khách</span>
                                    </td>

                                    <td class="fw-bold text-danger">
                                        <?= number_format($item['total_price']) ?> đ
                                    </td>

                                    <td>
                                        <?php 
                                            $status = $item['status'] ?? 'Chờ xử lý';
                                            $badgeClass = 'bg-secondary';
                                            
                                            // Dùng match hoặc if/else đều được
                                            if ($status == 'Chờ xử lý') $badgeClass = 'bg-warning text-dark';
                                            elseif ($status == 'Đã xác nhận') $badgeClass = 'bg-primary';
                                            elseif ($status == 'Hoàn thành') $badgeClass = 'bg-success';
                                            elseif ($status == 'Đã hủy') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill py-2 px-3"><?= $status ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" alt="No data" class="mb-3 opacity-50">
                                    <p class="text-muted fw-bold">Chưa có đơn đặt tour nào.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>