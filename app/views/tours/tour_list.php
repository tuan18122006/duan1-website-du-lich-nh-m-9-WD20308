    <div class="header-action" >
        <h2 class="header-title">Quản lý tour</h2>
        <a href="index.php?act=add_tour" class="btn-add"><i class="fas fa-plus"></i> Thêm mới</a>
    </div>



<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Danh mục</th>
                <th scope="col">Tên tour</th>
                <th scope="col">Ảnh</th>
                <th scope="col">Mô tả ngắn</th>
                <th scope="col">Số lượng</th>
                <th scope="col">Giá tiền</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tour_list)): ?>
                <?php foreach ($tour_list as $tour): ?>
                    <tr>
                        <td><?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($tour['tour_name']) ?></td>
                        <td>
                            <img src="assets/uploads/tours/<?= htmlspecialchars($tour['image_url']) ?>" style="height: 80px; object-fit: cover;">
                        </td>
                        <td><?= htmlspecialchars($tour['short_description']) ?></td>
                        <td><?= htmlspecialchars($tour['people']) ?></td>
                        <td><?= htmlspecialchars($tour['base_price']) ?></td>
                        <td>
                            <?= ($tour['status'] == 1) 
                                ? '<span style="color: green;">Đang mở</span>' 
                                : '<span style="color: red;">Đã đóng</span>' 
                            ?>
                        </td>
                        <td style="white-space: nowrap;">
                            <a href="index.php?act=tour_bookings&id=<?= $tour['tour_id'] ?>" 
                            class="btn btn-primary btn-sm" 
                            title="Xem danh sách khách & Kích hoạt tour">
                            <i class="fas fa-users-cog"></i> QL Khách
                            </a>
                            <a href="?act=update_tour&id=<?= htmlspecialchars($tour['tour_id']) ?>" class="btn btn-primary btn-sm" style="margin-right: 5px;">Sửa</a>
                            <a href="index.php?act=detail_tour&id=<?= $tour['tour_id'] ?>" class="btn btn-primary btn-sm" style="margin-right: 5px;">Chi tiết</a>
                            <a href="?act=delete_tour&id=<?= htmlspecialchars($tour['tour_id']) ?>"
                                onclick="return confirm('Bạn có chắc muốn xóa tour này?')"
                                class="btn btn-primary btn-sm">Xóa
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