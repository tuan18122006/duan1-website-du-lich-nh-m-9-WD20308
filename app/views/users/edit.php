
<body>

    <div class="breadcrumb">
        <a href="index.php?act=listkh"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a> / Cập nhật thông tin
    </div>

    <form action="index.php?act=updatekh" method="POST" enctype="multipart/form-data">
        <!-- Input ẩn chứa ID -->
        <input type="hidden" name="user_id" value="<?= $khachhang['user_id'] ?>">

        <!-- Input ẩn chứa tên ảnh cũ -->
        <input type="hidden" name="old_avatar" value="<?= $khachhang['avatar'] ?>">

        <div class="form-container">

            <!-- CỘT TRÁI -->
            <div class="left-col">
                <div class="avatar-preview">
                    <?php
                    $imgUrl = "";
                    $pathCheck = "assets/uploads/" . $khachhang['avatar'];
                    if (!empty($khachhang['avatar']) && file_exists($pathCheck)) {
                        $imgUrl = BASE_URL . "assets/uploads/" . $khachhang['avatar'];
                    } else {
                        $imgUrl = BASE_URL . "assets/uploads/default-avatar.png";
                    }
                    ?>
                    <img id="previewImg" src="<?= $imgUrl ?>"
                        onerror="this.onerror=null; this.src='<?= BASE_URL ?>assets/uploads/default-avatar.png';">
                </div>
                <label for="fileInput" class="btn-upload"><i class="fas fa-camera"></i> Thay ảnh mới</label>
                <input type="file" id="fileInput" name="avatar" onchange="previewFile()">
                <p style="font-size: 12px; color: #999; margin-top: 10px;">Nếu không chọn ảnh mới, ảnh cũ sẽ được giữ nguyên.</p>
            </div>

            <!-- CỘT PHẢI -->
            <div class="right-col">
                <h3 style="margin-top:0; color:#333; border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:20px;">
                    Thông tin tài khoản
                </h3>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Họ tên nhân viên (*)</label>
                        <input type="text" name="full_name" value="<?= $khachhang['full_name'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Tài khoản đăng nhập</label>
                        <!-- Thường không cho sửa username -->
                        <input type="text" name="username" value="<?= $khachhang['username'] ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Địa chỉ Email</label>
                        <input type="email" name="email" value="<?= $khachhang['email'] ?>">
                    </div>

                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" value="<?= $khachhang['phone'] ?>">
                    </div>

                    <div class="form-group full">
                        <label>Mật khẩu mới (Để trống nếu không muốn đổi)</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu mới...">
                    </div>

                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" name="birthday" value="<?= $khachhang['birthday'] ?>">
                    </div>

                <div style="text-align: right;">
                    <button type="submit" name="capnhat" class="btn-submit"> <i class="fas fa-check"></i> Cập nhật</button>
                </div>
            </div>
        </div>
    </form>

    <!-- JS Preview ảnh -->
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

