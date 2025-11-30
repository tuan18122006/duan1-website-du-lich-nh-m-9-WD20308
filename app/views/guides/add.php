<body>
    <div class="breadcrumb">
        <a href="index.php?act=list_guide"><i class="fas fa-arrow-left"></i> Quay lại</a> / Thêm Hướng dẫn viên
    </div>

    <form action="index.php?act=store_guide" method="POST" enctype="multipart/form-data">
        <div class="form-container">
            <!-- CỘT TRÁI: ẢNH -->
            <div class="left-col">
                <div class="avatar-preview">
                    <img id="previewImg" src="<?= BASE_URL ?>assets/uploads/default-avatar.png">
                </div>
                <label for="fileInput" class="btn-upload">Chọn ảnh đại diện</label>
                <input type="file" id="fileInput" name="avatar" onchange="previewFile()">
            </div>

            <!-- CỘT PHẢI: THÔNG TIN -->
            <div class="right-col">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Họ tên HDV (*)</label>
                        <input type="text" name="full_name" required placeholder="Nguyễn Văn A">
                    </div>
                    <div class="form-group">
                        <label>Tài khoản đăng nhập (*)</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu (*)</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone">
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" name="birthday">
                    </div>
                    
                    <!-- FIELD RIÊNG CỦA HDV -->
                    <div class="form-group" style="background: #f9f9f9; padding: 10px; border-radius: 5px;">
                        <label style="color: #e67e22;">Số năm kinh nghiệm</label>
                        <input type="number" name="experience_years" value="0" min="0">
                    </div>
                    <div class="form-group" style="background: #f9f9f9; padding: 10px; border-radius: 5px;">
                        <label style="color: #e67e22;">Ngôn ngữ thành thạo</label>
                        <input type="text" name="languages" placeholder="VD: Anh, Pháp, Trung">
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" name="add_guide" class="btn-submit"><i class="fas fa-save"></i> Lưu thông tin</button>
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