<div class="row">
    <div class="text">
        <h1>THÊM TOUR MỚI</h1>
        <?php 
            // Hiển thị thông báo lỗi/thành công từ Controller
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red; padding: 10px; border: 1px solid red;">' . htmlspecialchars($_SESSION['error']) . '</p>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<p style="color: green; padding: 10px; border: 1px solid green;">' . htmlspecialchars($_SESSION['success']) . '</p>';
                unset($_SESSION['success']);
            }
        ?>
    </div>
    <div class="main">
        <form action="index.php?act=add_tour" method="post" enctype="multipart/form-data">
            
            <div class="content">
                Tên Tour<br>
                <input type="text" name="tour_name" required 
                       value="<?= htmlspecialchars($tour_name ?? '') ?>">
            </div>

            <div class="content">
                Danh mục Tour<br>
                <select name="category_id" required>
                    <option value="">-- Chọn Danh mục --</option>
                    <?php 
                    // Tên biến categories trong Controller là $categories (không phải $tour_categories)
                    if (isset($categories) && is_array($categories)): 
                    ?>
                        <?php foreach ($categories as $cate): ?>
                            <option value="<?= htmlspecialchars($cate['category_id']) ?>"
                                <?= (($category_id ?? 0) == $cate['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cate['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">Không tìm thấy danh mục</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="content">
                Mô tả ngắn<br>
                <textarea name="short_description" cols="30" rows="3"><?= htmlspecialchars($short_description ?? '') ?></textarea>
            </div>

            <div class="content">
                Giá Tour (VND)<br>
                <input type="number" name="price" required 
                       value="<?= htmlspecialchars($price ?? '') ?>">
            </div>

            <div class="content">
                Số ngày/Đêm<br>
                <input type="number" name="duration_days" required 
                       value="<?= htmlspecialchars($duration_days ?? '') ?>">
            </div>
            
            <div class="content">
                Hình ảnh Tour<br>
                <input type="file" name="image">
            </div>

            <div class="content">
                Điểm Khởi hành<br>
                <input type="text" name="start_location" 
                       value="<?= htmlspecialchars($start_location ?? '') ?>">
            </div>

            <div class="content">
                Điểm Kết thúc<br>
                <input type="text" name="end_location" 
                       value="<?= htmlspecialchars($end_location ?? '') ?>">
            </div>

            <div class="content">
                Nhà cung cấp<br>
                <input type="text" name="supplier" 
                       value="<?= htmlspecialchars($supplier ?? '') ?>">
            </div>
            
            <div class="content">
                Chính sách/Ghi chú<br>
                <textarea name="policy" cols="30" rows="5"><?= htmlspecialchars($policy ?? '') ?></textarea>
            </div>

            <div class="content">
                Trạng thái<br>
                <select name="status">
                    <option value="Hoạt động" <?= (($status ?? '') == 'Hoạt động') ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="Đã kết thúc" <?= (($status ?? '') == 'Đã kết thúc') ? 'selected' : '' ?>>Đã kết thúc</option>
                </select>
            </div>

            <div class="content">
                Mô tả chi tiết<br>
                <textarea name="description" cols="30" rows="10"><?= htmlspecialchars($description ?? '') ?></textarea>
            </div>

            <div class="content">
                <input type="submit" name="them" value="THÊM TOUR">
                <a href="index.php?act=tour_list">Danh sách Tour</a>
            </div>
        </form>
    </div>
</div>