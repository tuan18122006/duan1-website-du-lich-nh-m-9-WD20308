<div class="container-fluid mt-4">
    <?php
        // Logic xác định tiêu đề và loại tour đang xem
        $current_type = $_GET['type'] ?? 0;
        $title_text = ($current_type == 1) ? "Quản lý Tour Tùy chọn (Khách thiết kế)" : "Quản lý Tour Mặc định";
        $badge_color = ($current_type == 1) ? "text-success" : "text-primary";
    ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="<?= $badge_color ?> fw-bold"><?= $title_text ?></h2>
        
        <?php if($current_type == 0): ?>
            <a href="index.php?act=add_tour" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        <?php endif; ?>
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
                        <th>
                            <?= ($current_type == 1) ? 'Ngân sách/Giá' : 'Giá cơ bản' ?>
                        </th>
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
                                    <?php if($current_type == 1): ?>
                                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem">Custom Request</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $img = !empty($tour['image_url']) ? "assets/uploads/tours/".$tour['image_url'] : "assets/images/no-image.jpg";
                                    ?>
                                    <a href="index.php?act=detail_tour&id=<?= $tour['tour_id'] ?>">
                                        <img src="<?= $img ?>" 
                                            style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;" 
                                            onerror="this.src='https://via.placeholder.com/60x40'">
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($tour['category_name'] ?? '---') ?></td>
                                <td class="text-danger fw-bold">
                                    <?= ($tour['base_price'] > 0) ? number_format($tour['base_price']) . ' đ' : 'Chưa báo giá' ?>
                                </td>
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
                                <i class="fas fa-calendar-alt"></i>
                                </a>

                                <a href="index.php?act=tour_bookings&id=<?= $tour['tour_id'] ?>" 
                                class="btn btn-primary btn-sm me-1" 
                                title="Quản lý Đơn đặt tour">
                                <i class="fas fa-file-invoice-dollar"></i>
                                </a>

                                <a href="index.php?act=tour_passenger_list&id=<?= $tour['tour_id'] ?>" 
                                class="btn btn-success btn-sm me-1" 
                                title="Danh sách khách đoàn chi tiết">
                                <i class="fas fa-users"></i> DS Khách
                                </a>

                                <a href="index.php?act=update_tour&id=<?= $tour['tour_id'] ?>" 
                                class="btn btn-warning btn-sm me-1" title="Sửa tour">
                                <i class="fas fa-edit"></i>
                                </a>
                                
                                <a href="index.php?act=delete_tour&id=<?= $tour['tour_id'] ?>"
                                onclick="return confirm('Bạn có chắc muốn xóa tour này?')"
                                class="btn btn-danger btn-sm" title="Xóa tour">
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
        // Giữ lại tham số type khi lọc
        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type') || 0;
        window.location.href = `index.php?act=tour_list&type=${type}&category_filter=${filterValue}`;
    }
</script>