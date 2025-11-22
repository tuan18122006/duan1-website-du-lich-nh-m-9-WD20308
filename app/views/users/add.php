<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm tài khoản</title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; max-width: 400px; }
        button { background-color: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer; font-weight: bold; }
        a { text-decoration: none; color: #007bff; margin-left: 10px; }
    </style>
</head>
<body>

<h2>Thêm tài khoản mới</h2>

<form action="index.php?act=storekh" method="POST">
    
    <div class="form-group">
        <label>Tên đăng nhập (Username):</label> 
        <input type="text" name="username" required placeholder="Nhập username...">
    </div>

    <div class="form-group">
        <label>Mật khẩu:</label> 
        <input type="password" name="password" required placeholder="Nhập mật khẩu...">
    </div>

    <div class="form-group">
        <label>Họ và tên:</label> 
        <input type="text" name="full_name" required placeholder="Ví dụ: Nguyễn Văn A">
    </div>

    <div class="form-group">
        <label>Email:</label> 
        <input type="email" name="email" placeholder="email@example.com">
    </div>

    <div class="form-group">
        <label>Số điện thoại:</label> 
        <input type="text" name="phone" placeholder="098...">
    </div>

    <div class="form-group">
        <label>Ngày sinh:</label> 
        <input type="date" name="birthday">
    </div>

    <div class="form-group">
        <label>Vai trò:</label>
        <select name="role">
            <option value="3">Khách hàng</option>
            <option value="2">Hướng dẫn viên / Nhân viên</option>
        </select>
    </div>

    <button type="submit" name="themoi">Thêm mới</button>
    <a href="index.php?act=listkh">Quay lại danh sách</a>
</form>

</body>
</html>