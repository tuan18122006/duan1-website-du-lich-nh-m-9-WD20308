<div class="row">
    <div class="text">
        <h1>CẬP NHẬT TOUR: <?= htmlspecialchars($tour['tour_name'] ?? '') ?></h1>
    </div>

    <form action="index.php?act=update_tour&id=<?= htmlspecialchars($tour['tour_id'] ?? '') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="tour_id" value="<?= htmlspecialchars($tour['tour_id'] ?? '') ?>">

        <div class="container-add-tour">
            <div class="col-form">

                <div class="form-group">
                    <label for="tour_name">Tên Tour (*)</label>
                    <input type="text" name="tour_name" id="tour_name" required
                        value="<?= htmlspecialchars($tour['tour_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="category_id">Danh mục Tour (*)</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">-- Chọn Danh mục --</option>
                        <?php if (isset($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $cate): ?>
                                <option value="<?= htmlspecialchars($cate['category_id']) ?>"
                                    <?= (($tour['category_id'] ?? 0) == $cate['category_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cate['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">Không tìm thấy danh mục</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Giá Tour (VND) (*)</label>
                    <input type="number" name="price" id="price" required
                        value="<?= htmlspecialchars($tour['price'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="duration_days">Số ngày/Đêm (*)</label>
                    <input type="number" name="duration_days" id="duration_days" required
                        value="<?= htmlspecialchars($tour['duration_days'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="start_location">Điểm Khởi hành</label>
                    <input type="text" name="start_location" id="start_location"
                        value="<?= htmlspecialchars($tour['start_location'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="end_location">Điểm Kết thúc</label>
                    <input type="text" name="end_location" id="end_location"
                        value="<?= htmlspecialchars($tour['end_location'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="supplier">Nhà cung cấp</label>
                    <input type="text" name="supplier" id="supplier"
                        value="<?= htmlspecialchars($tour['supplier'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="status">Trạng thái</label>
                    <select name="status" id="status">
                        <option value="Hoạt động" <?= (($tour['status'] ?? '') == 'Hoạt động') ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="Đã kết thúc" <?= (($tour['status'] ?? '') == 'Đã kết thúc') ? 'selected' : '' ?>>Đã kết thúc</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="short_description">Mô tả ngắn</label>
                    <textarea name="short_description" id="short_description" cols="30" rows="3"><?= htmlspecialchars($tour['short_description'] ?? '') ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="policy">Chính sách/Ghi chú</label>
                    <textarea name="policy" id="policy" cols="30" rows="5"><?= htmlspecialchars($tour['policy'] ?? '') ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="description">Mô tả chi tiết</label>
                    <textarea name="description" id="description" cols="30" rows="10"><?= htmlspecialchars($tour['description'] ?? '') ?></textarea>
                </div>

                <div class="image-upload-group">
                    <div class="image-preview-area">
                        <?php
                        $imgSrc = !empty($tour['image_url']) ? "assets/uploads/tours/" . htmlspecialchars($tour['image_url']) : "data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=";
                        $displayStyle = !empty($tour['image_url']) ? "block" : "none";
                        ?>
                        <img id="imagePreviewUpdate" src="<?= $imgSrc ?>" alt="Preview Ảnh" style="display: <?= $displayStyle ?>;">
                    </div>

                    <div class="file-input-wrapper">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="imageUploadUpdate">Hình ảnh Tour (Chọn nếu muốn thay đổi)</label>
                            <input type="file" name="image" id="imageUploadUpdate">
                            <input type="hidden" name="old_image_url" value="<?= htmlspecialchars($tour['image_url'] ?? '') ?>">
                            <small>Định dạng: JPEG, PNG. Kích thước 150x150px được khuyến nghị.</small>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="index.php?act=tour_list" class="btn btn-secondary">Danh sách Tour</a>
                    <input type="submit" name="capnhat" value="CẬP NHẬT TOUR" class="btn btn-primary">
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('imageUploadUpdate').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreviewUpdate');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>