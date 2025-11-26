    <div class="header-action" >
        <h2 class="header-title">Quản lý tour</h2>
        <a href="index.php?act=add_tour" class="btn-add"><i class="fas fa-plus"></i> Thêm mới</a>
    </div>

<div style="margin-bottom: 15px;">
    <select class="form-select"
        aria-label="Default select example"
        name="category_filter"
        onchange="applyFilter(this.value)">
        <option value="Tất cả" <?= ($category_filter ?? 'Tất cả') === 'Tất cả' ? 'selected' : '' ?>>Tất cả</option>
        <option value="1" <?= ($category_filter ?? 0) == 1 ? 'selected' : '' ?>>Trong nước</option>
        <option value="2" <?= ($category_filter ?? 0) == 2 ? 'selected' : '' ?>>Ngoài nước</option>
    </select>
</div>



<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Category</th>
                <th scope="col">Tour name</th>
                <th scope="col">Ảnh</th>
                <th scope="col">Short description</th>
                <th scope="col">Duration</th>
                <th scope="col">số khách tối đa</th>
                <th scope="col">Status</th>
                <th scope="col">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tour_list)): ?>
                <?php foreach ($tour_list as $tour): ?>
                    <tr>
                        <th scope="row"><?= htmlspecialchars($tour['tour_id']) ?></th>
                        <td><?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($tour['tour_name']) ?></td>
                        <td>
                            <img src="assets/uploads/tours/<?= htmlspecialchars($tour['image_url']) ?>" style="height: 80px; object-fit: cover;">
                        </td>
                        <td><?= htmlspecialchars($tour['short_description']) ?></td>
                        <td><?= htmlspecialchars($tour['duration_days']) ?></td>
                        <td><?= htmlspecialchars($tour['base_price']) ?></td>
                        <td>
                            <?= ($tour['status'] == 1) 
                                ? '<span style="color: green;">Đang mở</span>' 
                                : '<span style="color: red;">Đã đóng</span>' 
                            ?>
                        </td>
                        <td style="white-space: nowrap;">
                            <a href="?act=update_tour&id=<?= htmlspecialchars($tour['tour_id']) ?>" class="btn btn-warning" style="margin-right: 5px;">Sửa</a>
                            <a href="?act=delete_tour&id=<?= htmlspecialchars($tour['tour_id']) ?>"
                                onclick="return confirm('Bạn có chắc muốn xóa tour này?')"
                                class="btn btn-danger">Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">
                        <p>Không tìm thấy tour nào phù hợp với lựa chọn.</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function applyFilter(filterValue) {
        const baseUrl = 'index.php?act=tour_list';

        if (filterValue === 'Tất cả') {
            window.location.href = baseUrl;
        } else {
            const newUrl = baseUrl + '&category_filter=' + filterValue;

            window.location.href = newUrl;
        }
    }
</script>