<div class="card shadow border-0 mt-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i>Danh sách Khách hàng</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th>#ID</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Ngày khởi hành</th>
                    <th class="text-center">Số vé</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($bookings)): ?>
                    <?php foreach($bookings as $b): ?>
                    <tr>
                        <td>#<?= $b['id'] ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($b['customer_name']) ?></td>
                        <td><?= htmlspecialchars($b['customer_phone']) ?></td>
                        <td class="text-primary fw-bold">
                            <?= date('d/m/Y', strtotime($b['start_date'])) ?>
                        </td>
                        <td class="text-center"><?= $b['people'] ?></td>
                        <td class="text-danger fw-bold"><?= number_format($b['total_price']) ?> đ</td>
                        <td>
                            <?php 
                                $st = $b['status'];
                                $cls = match($st) {
                                    'Đã xác nhận' => 'bg-info text-dark',
                                    'Hoàn thành' => 'bg-success',
                                    'Đã hủy' => 'bg-danger',
                                    'Chờ xử lý' => 'bg-warning text-dark',
                                    default => 'bg-secondary'
                                };
                            ?>
                            <span class="badge <?= $cls ?>"><?= $st ?></span>
                        </td>
                        <td class="text-center">
                            <?php if ($st != 'Hoàn thành' && $st != 'Đã hủy'): ?>
                                <form method="POST" action="index.php?act=booking_detail&id=<?= $b['id'] ?>" class="d-inline" onsubmit="return confirm('Xác nhận khách đã giao dịch xong?');">
                                    <input type="hidden" name="action" value="mark_completed">
                                    <button type="submit" class="btn btn-success btn-sm" title="Đã thu tiền/Hoàn thành">
                                        <i class="fas fa-check"></i> XN Giao dịch
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <a href="index.php?act=booking_detail&id=<?= $b['id'] ?>" class="btn btn-sm btn-secondary" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center py-4">Chưa có khách đặt tour này.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>