<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success fw-bold"><i class="fas fa-drafting-compass me-2"></i>Danh sách Tour Thiết kế</h2>
        <p class="text-muted">Các tour được tạo ra từ yêu cầu riêng của khách hàng</p>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">Tên Tour / Yêu cầu</th>
                        <th>Khởi hành</th>
                        <th>Ngân sách/Giá</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tour_list)): ?>
                        <?php foreach ($tour_list as $tour): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                    <small class="text-muted fst-italic">Thiết kế theo yêu cầu</small>
                                </td>
                                <td>
                                    <?= date('d/m/Y', strtotime($tour['start_date'])) ?>
                                    <br>
                                    <small>(<?= $tour['duration_days'] ?> ngày)</small>
                                </td>
                                <td class="text-danger fw-bold">
                                    <?php if($tour['base_price'] > 0): ?>
                                        <?= number_format($tour['base_price']) ?> đ
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Chờ báo giá</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($tour['status'] == 1): ?>
                                        <span class="badge bg-success">Đang triển khai</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Đã đóng</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="index.php?act=tour_bookings&id=<?= $tour['tour_id'] ?>" 
                                       class="btn btn-outline-success btn-sm me-1" 
                                       title="Xem khách đặt">
                                       <i class="fas fa-users"></i> Khách
                                    </a>

                                    <?php if ($tour['base_price'] == 0): ?>
                                        
                                        <a href="index.php?act=tour_quote&id=<?= $tour['tour_id'] ?>" 
                                        class="btn btn-primary btn-sm me-1 shadow-sm">
                                        <i class="fas fa-calculator me-1"></i> Báo giá & Chốt
                                        </a>

                                    <?php else: ?>
                                        
                                        <button class="btn btn-secondary btn-sm me-1 shadow-sm" disabled style="opacity: 0.7; cursor: not-allowed;">
                                        <i class="fas fa-check-circle me-1"></i> Đã chốt giá
                                        </button>

                                    <?php endif; ?>
                                    
                                    <a href="index.php?act=delete_tour&id=<?= $tour['tour_id'] ?>"
                                       onclick="return confirm('Bạn có chắc muốn xóa tour này?')"
                                       class="btn btn-danger btn-sm">
                                       <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4">Chưa có tour thiết kế nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>