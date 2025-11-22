<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật tài khoản</title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; max-width: 400px; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; font-weight: bold; }
        a { text-decoration: none; color: #666; margin-left: 10px; }
    </style>
</head>
<body>

<h2>Cập nhật tài khoản: <?= $khachhang['username'] ?></h2>

<form action="index.php?act=updatekh&id=<?= $khachhang['user_id'] ?>" method="POST">
    
    <input type="hidden" name="user_id" value="<?= $khachhang['user_id'] ?>">
    
    <div class="form-group">
        <label>Tên đăng nhập (Username):</label> 
        <input type="text" name="username" value="<?= $khachhang['username'] ?>" required>
    </div>
    
    <div class="form-group">
        <label>Mật khẩu (Để trống nếu không đổi):</label> 
        <input type="password" name="password">
    </div>

    <div class="form-group">
        <label>Họ và tên:</label> 
        <input type="text" name="full_name" value="<?= $khachhang['full_name'] ?>">
    </div>
    
    <div class="form-group">
        <label>Email:</label> 
        <input type="email" name="email" value="<?= $khachhang['email'] ?>">
    </div>

    <div class="form-group">
        <label>Số điện thoại:</label> 
        <input type="text" name="phone" value="<?= $khachhang['phone'] ?>">
    </div>

    <div class="form-group">
        <label>Ngày sinh:</label> 
        <input type="date" name="birthday" value="<?= $khachhang['birthday'] ?>">
    </div>
    
    <div class="form-group">
        <label>Vai trò:</label>
        <select name="role">
            <option value="3" <?= $khachhang['role'] == 3 ? 'selected' : '' ?>>Khách hàng</option>
            <option value="2" <?= $khachhang['role'] == 2 ? 'selected' : '' ?>>Hướng dẫn viên / NV</option>
        </select>
    </div>
    
    <button type="submit" name="capnhat">Lưu cập nhật</button>
    <a href="index.php?act=listkh">Hủy</a>
</form>

</body>
</html>