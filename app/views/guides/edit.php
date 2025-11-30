<body>
    <div class="breadcrumb">
        <a href="index.php?act=list_guide"><i class="fas fa-arrow-left"></i> Quay lại</a> / Cập nhật HDV
    </div>

    <form action="index.php?act=update_guide" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?= $guide['user_id'] ?>">
        <input type="hidden" name="old_avatar" value="<?= $guide['avatar'] ?>">

        <div class="form-container">
            <div class="left-col">
                <div class="avatar-preview">
                    <?php 
                        $imgUrl = !empty($guide['avatar']) ? BASE_URL . "assets/uploads/" . $guide['avatar'] : BASE_URL . "assets/uploads/default-avatar.png";
                    ?>
                    <img id="previewImg" src="<?= $imgUrl ?>" onerror="this.src='<?= BASE_URL ?>assets/uploads/default-avatar.png';">
                </div>
                <label for="fileInput" class="btn-upload">Thay ảnh mới</label>
                <input type="file" id="fileInput" name="avatar" onchange="previewFile()">
            </div>

            <div class="right-col">
                <h3 class="mb-3 border-bottom pb-2">Thông tin Hướng dẫn viên</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Họ tên (*)</label>
                        <input type="text" name="full_name" value="<?= $guide['full_name'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tài khoản (Readonly)</label>
                        <input type="text" value="<?= $guide['username'] ?>" readonly class="bg-light">
                    </div>
                    <div class="form-group full">
                        <label>Mật khẩu mới (Để trống nếu không đổi)</label>
                        <input type="password" name="password">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= $guide['email'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?= $guide['phone'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" name="birthday" value="<?= isset($guide['birthday']) ? date('Y-m-d', strtotime($guide['birthday'])) : '' ?>">
                    </div>

                    <!-- RIÊNG HDV -->
                    <div class="form-group">
                        <label class="text-primary">Kinh nghiệm (năm)</label>
                        <input type="number" name="experience_years" value="<?= $guide['experience_years'] ?>">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Ngôn ngữ</label>
                        <input type="text" name="languages" value="<?= $guide['languages'] ?>">
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" name="update_guide" class="btn-submit"><i class="fas fa-check"></i> Cập nhật</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        function previewFile() {
            const preview = document.getElementById('previewImg');
            const file = document.getElementById('fileInput').files[0];
            const reader = new FileReader();
            reader.addEventListener("load", function() { preview.src = reader.result; }, false);
            if (file) reader.readAsDataURL(file);
        }
    </script>
</body>