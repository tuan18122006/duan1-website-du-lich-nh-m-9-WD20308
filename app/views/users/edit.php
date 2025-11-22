<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cập nhật nhân sự</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        .form-container {
            display: flex;
            gap: 30px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            max-width: 1100px;
            margin: 0 auto;
        }

        /* CỘT TRÁI: ẢNH */
        .left-col {
            width: 30%;
            text-align: center;
            border-right: 1px solid #eee;
            padding-right: 30px;
        }

        .avatar-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #eee;
            margin: 0 auto 20px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #e0e0e0;
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-upload {
            display: inline-block;
            padding: 8px 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            background: #fff;
            font-size: 14px;
            color: #555;
            transition: 0.3s;
        }

        .btn-upload:hover {
            background: #f8f9fa;
            border-color: #ccc;
        }

        input[type="file"] {
            display: none;
        }

        /* CỘT PHẢI: FORM */
        .right-col {
            width: 70%;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input:focus {
            border-color: #3498db;
            outline: none;
        }

        /* Style cho input readonly */
        input[readonly] {
            background-color: #f9f9f9;
            color: #777;
            cursor: not-allowed;
        }

        .btn-submit {
            background: #f39c12;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            font-weight: bold;
        }

        .btn-submit:hover {
            background: #e67e22;
        }

        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .breadcrumb a {
            text-decoration: none;
            color: #3498db;
        }
    </style>
</head>

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

                    <div class="form-group">
                        <label>Vai trò (*)</label>
                        <select name="role">
                            <option value="3" <?= $khachhang['role'] == 3 ? 'selected' : '' ?>>Khách hàng</option>
                            <option value="2" <?= $khachhang['role'] == 2 ? 'selected' : '' ?>>Nhân viên</option>
                        </select>
                    </div>
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

</html>