<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-success fw-bold"><i class="fas fa-file-signature me-2"></i>Đơn Yêu cầu Thiết kế</h2>
            <p class="text-muted">Danh sách khách hàng gửi yêu cầu thiết kế tour riêng</p>
        </div>
        <a href="index.php?act=booking_add_custom" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-2"></i> Tạo Yêu cầu Mới
        </a>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th>Khách hàng</th>
                        <th>Nội dung yêu cầu</th>
                        <th>Dự kiến</th>
                        <th>Báo giá (VNĐ)</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $item): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= $item['id'] ?></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($item['customer_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($item['customer_phone']) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold text-success"><?= htmlspecialchars($item['tour_name']) ?></div>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($item['start_date'])) ?>
                                <br><small><?= $item['people'] ?> khách</small>
                            </td>
                            
                            <td class="fw-bold text-danger">
                                <?php if($item['total_price'] > 0): ?>
                                    <?= number_format($item['total_price']) ?> đ
                                <?php else: ?>
                                    <span class="text-muted fst-italic">---</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php 
                                    $status = $item['status'];
                                    
                                    if ($item['total_price'] == 0) {
                                        echo '<span class="badge bg-warning text-dark">Chờ báo giá</span>';
                                    } 
                                    else {
                                        $badgeClass = match($status) {
                                            'Chờ xử lý' => 'bg-info text-dark',
                                            'Đã xác nhận' => 'bg-primary',
                                            'Hoàn thành' => 'bg-success',
                                            'Đã hủy' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        
                                        $displayStatus = ($status == 'Chờ xử lý') ? 'Đã báo giá' : $status;
                                        
                                        echo "<span class='badge $badgeClass'>$displayStatus</span>";
                                    }
                                ?>
                            </td>

                            <td class="text-end pe-4">
                                <a href="index.php?act=booking_detail&id=<?= $item['id'] ?>" 
                                   class="btn btn-outline-success btn-sm">
                                   <i class="fas fa-eye"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-4">Chưa có yêu cầu nào.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>