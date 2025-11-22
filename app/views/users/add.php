<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm nhân sự</title>
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
        }

        .btn-upload:hover {
            background: #f8f9fa;
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
            /* Fix lỗi tràn input */
        }

        input:focus {
            border-color: #3498db;
            outline: none;
        }

        .btn-submit {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background: #2980b9;
        }
    </style>
</head>

<body>

    <h3><a href="index.php?act=listkh" style="text-decoration:none; color:#999;">&laquo; Quay lại</a> / Thêm mới nhân viên</h3>

    <form action="index.php?act=storekh" method="POST" enctype="multipart/form-data">
        <div class="form-container">

            <!-- CỘT TRÁI -->
            <div class="left-col">
                <div class="avatar-preview">
                    <img id="previewImg" src="https://via.placeholder.com/150?text=No+Image" alt="Preview">
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

</html>