

<body>

    <div class="breadcrumb">
        <a href="index.php?act=listkh"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a> /  Thêm mới tài khoản
    </div>

    <form action="index.php?act=storekh" method="POST" enctype="multipart/form-data">
        <div class="form-container">

            <!-- CỘT TRÁI -->
            <div class="left-col">
                <div class="avatar-preview">
                    <img id="previewImg" src="https://via.placeholder.com/150?text=No+Image" >
                </div>
                <label for="fileInput" class="btn-upload">Chọn ảnh</label>
                <input type="file" id="fileInput" name="avatar" onchange="previewFile()">
                <p style="font-size: 12px; color: #999; margin-top: 10px;">Định dạng: JPEG, PNG (Tỉ lệ 1:1)</p>
            </div>

            <!-- CỘT PHẢI -->
            <div class="right-col">
                <div class="form-grid">

                    <div class="form-group">
                        <label>Họ tên nhân viên (*)</label>
                        <input type="text" name="full_name" required placeholder="VD: Nguyễn Văn A">
                    </div>

                    <div class="form-group">
                        <label>Tài khoản đăng nhập (*)</label>
                        <input type="text" name="username" required placeholder="VD: nguyenvan_a">
                    </div>

                    <div class="form-group">
                        <label>Địa chỉ Email</label>
                        <input type="email" name="email" placeholder="VD: email@gmail.com">
                    </div>

                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" placeholder="09xxxx">
                    </div>

                    <div class="form-group">
                        <label>Mật khẩu (*)</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" name="birthday">
                    </div>

                    <div class="form-group full">
                        <label>Vai trò (*)</label>
                        <select name="role">
                            <option value="0">Khách hàng (Người dùng)</option>
                            <option value="2">Nhân viên</option>
                            <!-- Đã xóa Admin (value="1") -->
                        </select>
                    </div>
                </div>

                <div style="text-align: right;">
                    <button type="submit" name="themoi" class="btn-submit"> <i class="fas fa-save"></i> Lưu lại</button>
                </div>
            </div>
        </div>
    </form>

    <!-- JS để hiển thị ảnh xem trước -->
    <script>
        function previewFile() {
            const preview = document.getElementById('previewImg');
            const file = document.getElementById('fileInput').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function() {
                preview.src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>

