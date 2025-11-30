<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary fw-bold">Quản lý Tour: <?= htmlspecialchars($tour['tour_name']) ?></h3>
        <a href="index.php?act=tour_list" class="btn btn-secondary">Quay lại danh sách</a>
    </div>

    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="mb-1"><strong>Ngày khởi hành:</strong> <?= date('d/m/Y', strtotime($tour['start_date'])) ?></p>
                    <p class="mb-1"><strong>Giá vé:</strong> <?= number_format($tour['base_price']) ?> VNĐ</p>
                    <p class="mb-1"><strong>Trạng thái:</strong> 
                        <?php if($tour['status'] == 1): ?> <span class="badge bg-warning text-dark">Đang gom khách</span>
                        <?php elseif($tour['status'] == 2): ?> <span class="badge bg-success">Đang hoạt động</span>
                        <?php else: ?> <span class="badge bg-danger">Đã dừng/Hủy</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <?php 
                        $max = $tour['people'];
                        $percent = ($max > 0) ? round(($current_people / $max) * 100) : 0;
                    ?>
                    <h5 class="fw-bold">Tiến độ: <?= $current_people ?> / <?= $max ?> khách</h5>
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: <?= $percent ?>%"><?= $percent ?>%</div>
                    </div>

                    <?php if($tour['status'] == 1): ?>
                        <form method="POST">
                            <button type="submit" name="activate_tour" class="btn btn-success fw-bold" 
                                <?= ($current_people < $max) ? 'disabled' : '' ?>>
                                <i class="fas fa-play-circle"></i> KÍCH HOẠT TOUR
                            </button>
                            <?php if($current_people < $max): ?>
                                <div class="text-danger small mt-1">Chưa đủ số lượng khách</div>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-users"></i> Danh sách khách hàng</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Tên khách</th>
                        <th>SĐT</th>
                        <th>Số vé</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái Đơn</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($bookings)): ?>
                        <?php foreach($bookings as $b): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($b['customer_name']) ?></td>
                            <td><?= htmlspecialchars($b['customer_phone']) ?></td>
                            <td class="text-center"><span class="badge bg-secondary"><?= $b['people'] ?></span></td>
                            <td class="text-danger fw-bold"><?= number_format($b['total_price']) ?> đ</td>
                            <td>
                                <?php if($b['status'] == 'Chờ xử lý'): ?> <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                <?php elseif($b['status'] == 'Đã xác nhận'): ?> <span class="badge bg-primary">Đã xác nhận</span>
                                <?php elseif($b['status'] == 'Đã hủy'): ?> <span class="badge bg-danger">Đã hủy</span>
                                <?php else: ?> <span class="badge bg-success">Hoàn thành</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?act=booking_detail&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    Xử lý đơn
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có khách nào đặt tour này.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>