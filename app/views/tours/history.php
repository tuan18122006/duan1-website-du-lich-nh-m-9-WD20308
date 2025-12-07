<div class="container mt-4">
    <h3 class="text-uppercase fw-bold text-secondary mb-4"><i class="fas fa-history"></i> Lịch sử Tour đã chạy</h3>
    
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>ID</th>
                        <th>Tên Tour</th>
                        <th>Ngày đi</th>
                        <th>Ngày về</th>
                        <th>Số khách</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($history_list)): ?>
                        <?php foreach($history_list as $tour): ?>
                        <tr>
                            <td>#<?= $tour['tour_id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($tour['tour_name']) ?></td>
                            <td><?= date('d/m/Y', strtotime($tour['start_date'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($tour['end_date'])) ?></td>
                            <td><?= $tour['people'] ?></td>
                            <td>
                                <?php if($tour['status'] == 3): ?>
                                    <span class="badge bg-success">Hoàn thành</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Đã hủy</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?act=tour_bookings&id=<?= $tour['tour_id'] ?>" class="btn btn-sm btn-outline-secondary">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-4">Chưa có lịch sử tour nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>