<style>
    /* Copy CSS giống file add_tour.php */
    .form-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 1100px; margin: 20px auto; }
    .form-title { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; font-size: 24px; font-weight: bold; color: #333; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #444; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
    .form-control:focus { border-color: #3498db; outline: none; }
    .btn-group { text-align: right; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px; }
    .btn { padding: 10px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
    .btn-submit { background: #f39c12; color: white; }
    .btn-submit:hover { background: #d35400; }
    .btn-cancel { background: #95a5a6; color: white; margin-right: 10px; }
    .current-img { width: 100px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; margin-bottom: 5px; }
</style>

<div class="form-container">
    <h1 class="form-title">CẬP NHẬT TOUR: <?= htmlspecialchars($tour['tour_name']) ?></h1>

    <!-- Form gửi ID trên URL để Controller biết đang sửa tour nào -->
    <form action="index.php?act=update_tour&id=<?= $tour['tour_id'] ?>" method="post" enctype="multipart/form-data">
        
        <!-- Input ẩn chứa ID tour -->
        <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">

            <!-- CỘT TRÁI -->
            <div>
                <div class="form-group">
                    <label>Tên Tour (*)</label>
                    <input type="text" name="tour_name" class="form-control" required value="<?= htmlspecialchars($tour['tour_name']) ?>">
                </div>

                <div class="form-group">
                    <label>Danh mục (*)</label>
                    <select name="category_id" class="form-control" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>" 
                                <?= ($tour['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Giá Tour (VNĐ) (*)</label>
                    <input type="number" name="base_price" class="form-control" required value="<?= $tour['base_price'] ?>">
                </div>

                <!-- TRẠNG THÁI -->
                <div class="form-group">
                    <label>Trạng thái (*)</label>
                    <select name="status" class="form-control">
                        <option value="1" <?= ($tour['status'] == 1) ? 'selected' : '' ?>>Đang mở bán</option>
                        <option value="0" <?= ($tour['status'] == 0) ? 'selected' : '' ?>>Đã đóng (Hết chỗ)</option>
                    </select>
                </div>


            <!-- CỘT PHẢI -->
            <div>
                <div class="form-group">
                    <label>Nhà cung cấp</label>
                    <input type="text" name="supplier" class="form-control" value="<?= htmlspecialchars($tour['supplier']) ?>">
                </div>

                <!-- ẢNH ĐẠI DIỆN -->
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

        <!-- PHẦN FULL WIDTH -->
        <div class="form-group">
            <label>Mô tả ngắn</label>
            <textarea name="short_description" class="form-control" rows="3"><?= htmlspecialchars($tour['short_description']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="6"><?= htmlspecialchars($tour['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Chính sách / Ghi chú</label>
            <textarea name="policy" class="form-control" rows="4"><?= htmlspecialchars($tour['policy']) ?></textarea>
        </div>

        <!-- NÚT BẤM -->
        <div class="btn-group">
            <a href="index.php?act=tour_list" class="btn btn-cancel">Hủy bỏ</a>
            <button type="submit" name="capnhat" class="btn btn-submit">CẬP NHẬT TOUR</button>
        </div>

    </form>
</div>