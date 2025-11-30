<style>
    .detail-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 1000px; margin: 20px auto; }
    .detail-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
    .tour-title { font-size: 24px; font-weight: bold; color: #333; margin: 0; }
    .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; color: white; }
    .badge-success { background-color: #2ecc71; }
    .badge-danger { background-color: #e74c3c; }
    
    .detail-grid { display: grid; grid-template-columns: 350px 1fr; gap: 30px; }
    .img-main { width: 100%; height: 250px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
    
    .info-row { margin-bottom: 15px; display: flex; border-bottom: 1px dashed #eee; padding-bottom: 10px; }
    .info-label { font-weight: bold; width: 150px; color: #555; }
    .info-value { flex: 1; color: #333; font-weight: 500; }
    
    .desc-box { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px; border: 1px solid #eee; }
    .desc-title { font-weight: bold; margin-bottom: 10px; display: block; color: #3498db; font-size: 16px; }
    
    .btn-back { background: #95a5a6; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; }
    .btn-edit { background: #f39c12; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; margin-left: 10px; }
</style>

<div class="detail-container">
    <!-- HEADER -->
    <div class="detail-header">
        <div>
            <h1 class="tour-title"><?= htmlspecialchars($tour['tour_name']) ?></h1>
            <span style="color: #777; font-size: 14px;">Mã Tour: #<?= $tour['tour_id'] ?></span>
        </div>
        <div>
            <?= ($tour['status'] == 1) 
                ? '<span class="badge badge-success">Đang mở</span>' 
                : '<span class="badge badge-danger">Đã đóng</span>' 
            ?>
        </div>
    </div>

    <div class="detail-grid">
        <!-- CỘT TRÁI: ẢNH -->
        <div>
            <?php 
                $imgUrl = !empty($tour['image_url']) ? "assets/uploads/tours/" . $tour['image_url'] : "assets/uploads/default-image.png";
            ?>
            <img src="<?= $imgUrl ?>" class="img-main" onerror="this.src='assets/uploads/default-image.png'">
            
            <div style="margin-top: 20px;">
                <div class="info-row">
                    <span class="info-label">Giá Tour:</span>
                    <span class="info-value" style="color: #e74c3c; font-size: 18px; font-weight: bold;">
                        <?= number_format($tour['base_price']) ?> VNĐ
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Số lượng khách:</span>
                    <span class="info-value"><?= $tour['people'] ?> người</span>
                </div>
            </div>
        </div>

        <!-- CỘT PHẢI: THÔNG TIN -->
        <div>
            <div class="info-row">
                <span class="info-label">Danh mục:</span>
                <span class="info-value">
                    <?php 
                        // Tìm tên danh mục trong mảng categories
                        $catName = 'Chưa phân loại';
                        foreach($categories as $cat) {
                            if($cat['category_id'] == $tour['category_id']) {
                                $catName = $cat['category_name'];
                                break;
                            }
                        }
                        echo htmlspecialchars($catName);
                    ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Hướng dẫn viên:</span>
                <span class="info-value">
                    <?php 
                        $guideName = 'Chưa chỉ định';
                        if (!empty($tour['guide_id'])) {
                            foreach($guides as $g) {
                                if($g['guide_id'] == $tour['guide_id']) {
                                    $guideName = $g['full_name'];
                                    break;
                                }
                            }
                        }
                        echo htmlspecialchars($guideName);
                    ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Ngày đi:</span>
                <span class="info-value"><?= !empty($tour['start_date']) ? date('d/m/Y', strtotime($tour['start_date'])) : '---' ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">Ngày về:</span>
                <span class="info-value"><?= !empty($tour['end_date']) ? date('d/m/Y', strtotime($tour['end_date'])) : '---' ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">Thời gian:</span>
                <span class="info-value"><?= $tour['duration_days'] ?> ngày</span>
            </div>

            <div class="info-row">
                <span class="info-label">Nhà cung cấp:</span>
                <span class="info-value"><?= htmlspecialchars($tour['supplier']) ?></span>
            </div>
        </div>
    </div>

    <!-- PHẦN MÔ TẢ -->
    <div class="desc-box">
        <span class="desc-title"><i class="fas fa-info-circle"></i> Mô tả ngắn</span>
        <p><?= nl2br(htmlspecialchars($tour['short_description'])) ?></p>
    </div>

    <div class="desc-box">
        <span class="desc-title"><i class="fas fa-file-alt"></i> Mô tả chi tiết</span>
        <p><?= nl2br(htmlspecialchars($tour['description'])) ?></p>
    </div>

    <div class="desc-box">
        <span class="desc-title"><i class="fas fa-shield-alt"></i> Chính sách Tour</span>
        <p><?= nl2br(htmlspecialchars($tour['policy'])) ?></p>
    </div>

    <div style="margin-top: 30px; text-align: right;">
        <a href="index.php?act=tour_list" class="btn-back">Quay lại</a>
        <a href="index.php?act=update_tour&id=<?= $tour['tour_id'] ?>" class="btn-edit">Sửa thông tin</a>
    </div>
</div>