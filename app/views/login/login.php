<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Login Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <div class="bg-image"></div>

    <div class="wrapper">
        <input type="radio" name="slide" id="login" checked>
        <input type="radio" name="slide" id="signup">

        <div class="title-text">
            <div class="title login">Chào mừng trở lại!</div>
            <div class="title signup">Bắt đầu hành trình</div>
        </div>

        <div class="form-container">
            <div class="slide-controls">
                <label for="login" class="slide login">Đăng Nhập</label>
                <label for="signup" class="slide signup">Đăng Ký</label>
                <div class="slider-tab"></div>
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
                    <?php if(!empty($data['error'])): ?>
                        <p style="color:red; margin-top:10px; text-align:center;"><?= $data['error'] ?></p>
                    <?php endif; ?>
                </form>

                <!-- FORM SIGNUP (tạm thời chưa kết nối) -->
                <form action="#" class="signup">
                    <div class="field">
                        <input type="text" placeholder="Họ và Tên" required>
                    </div>
                    <div class="field">
                        <input type="text" placeholder="Email" required>
                    </div>
                    <div class="field">
                        <input type="password" placeholder="Mật khẩu" required>
                    </div>
                    <div class="field">
                        <input type="password" placeholder="Nhập lại mật khẩu" required>
                    </div>
                    <div class="field btn">
                        <input type="submit" value="Đăng Ký">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
