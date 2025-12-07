<style>
    /* CSS nội bộ cho form đẹp hơn */
    .form-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 1100px; margin: 20px auto; }
    .form-title { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; font-size: 24px; font-weight: bold; color: #333; }
    
    
    .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #444; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
    .form-control:focus { border-color: #3498db; outline: none; }
    
    .btn-group { text-align: right; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px; }
    .btn { padding: 10px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
    .btn-submit { background: #3498db; color: white; }
    .btn-submit:hover { background: #2980b9; }
    .btn-cancel { background: #95a5a6; color: white; margin-right: 10px; }
    
    .preview-box { margin-top: 10px; border: 1px dashed #ccc; padding: 10px; text-align: center; }
    .preview-img { max-width: 100%; max-height: 200px; display: none; }
</style>

<div class="form-container">
    <h1 class="form-title">THÊM TOUR MỚI</h1>

    <!-- Form gửi dữ liệu sang hàm addTour của Controller -->
    <form action="index.php?act=add_tour" method="post" enctype="multipart/form-data">
        
            <!-- CỘT TRÁI -->
            <div>
                <div class="form-group">
                    <label>Tên Tour (*)</label>
                    <input type="text" name="tour_name" class="form-control" required 
                           placeholder="Ví dụ: Tour Hạ Long 3 ngày 2 đêm"
                           value="<?= htmlspecialchars($sticky_data['tour_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Danh mục (*)</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Chọn Danh mục --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>" 
                                    <?= (isset($sticky_data['category_id']) && $sticky_data['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Giá Tour (VNĐ) (*)</label>
                    <input type="number" name="base_price" class="form-control" required min="0"
                           value="<?= htmlspecialchars($sticky_data['base_price'] ?? '') ?>">
                </div>

                <!-- TRẠNG THÁI -->
                <div class="form-group">
                    <label>Trạng thái (*)</label>
                    <select name="status" class="form-control">
                        <option value="1" <?= (isset($sticky_data['status']) && $sticky_data['status'] == 1) ? 'selected' : '' ?>>Đang mở bán</option>
                        <option value="0" <?= (isset($sticky_data['status']) && $sticky_data['status'] == 0) ? 'selected' : '' ?>>Đã đóng (Hết chỗ)</option>
                    </select>
                </div>

            <!-- CỘT PHẢI -->
            <div>
                <div class="form-group">
                    <label>Nhà cung cấp</label>
                    <input type="text" name="supplier" class="form-control"
                           value="<?= htmlspecialchars($sticky_data['supplier'] ?? '') ?>">
                </div>
                
                <!-- ẢNH ĐẠI DIỆN -->
                <div class="form-group">
                    <label>Ảnh đại diện (*)</label>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                    <div class="preview-box">
                        <img id="imagePreview" class="preview-img" src="#" alt="Ảnh xem trước">
                        <span id="previewText" style="color:#999; font-size:13px;">Chưa chọn ảnh</span>
                    </div>
                </div>
            </div>

        <!-- PHẦN FULL WIDTH -->
        <div class="form-group">
            <label>Mô tả ngắn</label>
            <textarea name="short_description" class="form-control" rows="3"><?= htmlspecialchars($sticky_data['short_description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="6"><?= htmlspecialchars($sticky_data['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Chính sách / Ghi chú</label>
            <textarea name="policy" class="form-control" rows="4"><?= htmlspecialchars($sticky_data['policy'] ?? '') ?></textarea>
        </div>

        <!-- NÚT BẤM -->
        <div class="btn-group">
            <a href="index.php?act=tour_list" class="btn btn-cancel">Hủy bỏ</a>
            <button type="submit" class="btn btn-submit">THÊM MỚI</button>
        </div>

    </form>
</div>

<script>
    // Script xem trước ảnh
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'inline-block';
                document.getElementById('previewText').style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });
</script>