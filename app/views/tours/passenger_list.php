<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4><i class="fas fa-users me-2"></i>Danh sách khách đoàn</h4>
            <p class="mb-0 text-muted">
                <?php if(isset($is_custom) && $is_custom): ?>
                    <span class="badge bg-warning text-dark">Tour Thiết kế</span>
                <?php else: ?>
                    <span class="badge bg-primary">Tour Cố định</span>
                <?php endif; ?>
                <strong><?= htmlspecialchars($tour['tour_name']) ?></strong>
            </p>
            
            <?php if(isset($schedule) && !empty($schedule)): ?>
                <small class="text-primary fw-bold">
                    Khởi hành: <?= date('d/m/Y H:i', strtotime($schedule['start_date'])) ?>
                </small>
            <?php elseif(isset($tour['start_date'])): ?>
                <small class="text-primary fw-bold">
                    Khởi hành: <?= date('d/m/Y', strtotime($tour['start_date'])) ?>
                </small>
            <?php endif; ?>
        </div>
        
        <?php if(isset($is_custom) && $is_custom): ?>
            <a href="index.php?act=custom_tour_list" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại DS Tour Yêu cầu
            </a>
        <?php else: ?>
            <a href="index.php?act=tour_passenger_list&id=<?= $tour['tour_id'] ?>" class="btn btn-secondary">
                <i class="fas fa-calendar-alt"></i> Chọn ngày khác
            </a>
        <?php endif; ?>
    </div>
    
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>STT</th>
                        <th>Họ và Tên</th>
                        <th>Giới tính</th>
                        <th>Tuổi</th>
                        <th>Người đặt (Liên hệ)</th>
                        <th>SĐT Liên hệ</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($passengers)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                                Chưa có hành khách nào trong danh sách này.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($passengers as $index => $p): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($p['full_name']) ?></td>
                            <td><?= $p['gender'] ?></td>
                            <td><?= $p['age'] ?></td>
                            
                            <td>
                                <?php if(isset($p['booker_name'])): ?>
                                    <?= htmlspecialchars($p['booker_name']) ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">(Theo đoàn)</span>
                                <?php endif; ?>
                            </td>

                            <td><?= $p['customer_phone'] ?></td>
                            
                            <td>
                                <?php if($p['status'] == 'Đã xác nhận' || $p['status'] == 'Hoàn thành'): ?>
                                    <span class="badge bg-success">Đã xác nhận</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-3 text-end">
        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="fas fa-print"></i> In danh sách
        </button>
    </div>
</div>