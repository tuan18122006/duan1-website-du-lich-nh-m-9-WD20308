<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">Quản lý Tour</h2>
        <a href="index.php?act=add_tour" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <select class="form-select" onchange="applyFilter(this.value)">
                        <option value="Tất cả" <?= ($category_filter ?? 'Tất cả') === 'Tất cả' ? 'selected' : '' ?>>Tất cả danh mục</option>
                        <?php if(!empty($categories)): ?>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>" <?= ($category_filter ?? '') == $cat['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">Tên Tour</th>
                        <th>Ảnh</th>
                        <th>Danh mục</th>
                        <th>Giá cơ bản</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tour_list)): ?>
                        <?php foreach ($tour_list as $tour): ?>
                            <tr>
                                <td class="ps-3 fw-bold">
                                    <a href="index.php?act=detail_tour&id=<?= $tour['tour_id'] ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($tour['tour_name']) ?>
                                    </a>
                                    <div class="small text-muted">ID: #<?= $tour['tour_id'] ?></div>
                                </td>
                                <td>
                                    <img src="assets/uploads/tours/<?= htmlspecialchars($tour['image_url']) ?>" 
                                         style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td><?= htmlspecialchars($tour['category_name'] ?? '---') ?></td>
                                <td class="text-danger fw-bold"><?= number_format($tour['base_price']) ?> đ</td>
                                <td>
                                    <?php if($tour['status'] == 1): ?>
                                        <span class="badge bg-success">Đang mở</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Đã đóng</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="index.php?act=tour_schedules&id=<?= $tour['tour_id'] ?>" 
                                       class="btn btn-info btn-sm text-white me-1" 
                                       title="Quản lý lịch khởi hành">
                                       <i class="fas fa-calendar-alt"></i> QL Lịch
                                    </a>

                                    <a href="index.php?act=tour_bookings&id=<?= $tour['tour_id'] ?>" 
                                       class="btn btn-primary btn-sm me-1" 
                                       title="Xem danh sách khách">
                                       <i class="fas fa-users"></i> Khách
                                    </a>

                                    <a href="index.php?act=update_tour&id=<?= $tour['tour_id'] ?>" 
                                       class="btn btn-warning btn-sm me-1">
                                       <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="index.php?act=delete_tour&id=<?= $tour['tour_id'] ?>"
                                       onclick="return confirm('Bạn có chắc muốn xóa tour này?')"
                                       class="btn btn-danger btn-sm">
                                       <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Không tìm thấy tour nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function applyFilter(filterValue) {
        window.location.href = 'index.php?act=tour_list&category_filter=' + filterValue;
    }
</script>