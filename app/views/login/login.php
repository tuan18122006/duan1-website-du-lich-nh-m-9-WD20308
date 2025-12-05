<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Login Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>
        /* Chọn nút đăng nhập trong form login */
        /* Chọn nút đăng nhập */
        form.login .field.btn input[type="submit"] {
            border: 2px solid #007BFF;
            /* viền xanh */
            background-color: #007BFF;
            /* nền xanh */
            color: white;
            /* chữ trắng */
            padding: 10px 20px;
            /* khoảng cách trong nút */
            font-size: 16px;
            border-radius: 5px;
            /* bo góc */
            cursor: pointer;
            /* con trỏ tay khi hover */
            transition: 0.3s;
            /* hiệu ứng mượt khi hover */
        }

        /* Hiệu ứng hover (tăng độ sáng nền) */
        form.login .field.btn input[type="submit"]:hover {
            background-color: #0056b3;
            /* xanh đậm hơn khi hover */
            border-color: #0056b3;
            /* viền cùng màu nền khi hover */
        }
    </style>
</head>

<body>
    <div class="bg-image"></div>

    <div class="wrapper">
        <input type="radio" name="slide" id="login" checked>

        <div class="title-text">
            <div class="title login">Chào mừng trở lại!</div>
        </div>

        <div class="form-container">
            <div class="slide-controls">
                <label for="login" class="slide login">Đăng Nhập</label>>
            </div>

            <div class="form-inner">
                <!-- FORM LOGIN -->
                <form action="index.php?act=login_action" method="POST" class="login">
                    <div class="field">
                        <input type="text" name="username" placeholder="Email / Tên đăng nhập" required>
                    </div>
                    <div class="field">
                        <input type="password" name="password" placeholder="Mật khẩu" required>
                    </div>
                    <div class="field btn">
                        <input type="submit" value="Đăng Nhập">
                    </div>

                    <!-- Hiển thị lỗi nếu login sai -->
                    <?php if (!empty($data['error'])): ?>
                        <p style="color:red; margin-top:10px; text-align:center;"><?= $data['error'] ?></p>
                    <?php endif; ?>
                </form>

            </div>
        </div>
    </div>
</body>

</html>