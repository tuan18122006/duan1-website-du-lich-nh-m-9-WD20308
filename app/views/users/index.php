<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tài khoản</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; white-space: nowrap; } /* Tiêu đề không xuống dòng */
        
        .btn { text-decoration: none; padding: 5px 10px; color: white; border-radius: 4px; display: inline-block;}
        .btn-edit { background-color: #007bff; } /* Màu xanh dương */
        .btn-delete { background-color: #dc3545; } /* Màu đỏ */
        .btn-add { background-color: #28a745; padding: 10px 15px; font-weight: bold; margin-bottom: 15px; } /* Màu xanh lá */
    </style>
</head>
<body>

    <h2>Danh sách Khách hàng & Nhân viên</h2>
    
    <a href="index.php?act=addkh" class="btn btn-add">+ Thêm tài khoản mới</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Họ và tên</th>   <th>Email</th>
                <th>SĐT</th>         <th>Ngày sinh</th>   <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listkhachhang)): ?>
                <?php foreach ($listkhachhang as $kh): ?>
                <tr>
                    <td><?= $kh['user_id'] ?></td>
                    
                    <td><?= $kh['username'] ?></td>
                    
                    <td><?= $kh['full_name'] ?></td>
                    
                    <td><?= $kh['email'] ?></td>

                    <td><?= $kh['phone'] ?></td>

                    <td>
                        <?= !empty($kh['birthday']) ? date('d/m/Y', strtotime($kh['birthday'])) : '' ?>
                    </td>
                    
                    <td>
                        <?php 
                            if ($kh['role'] == 1) echo '<span style="color:red; font-weight:bold">Admin</span>';
                            elseif ($kh['role'] == 2) echo '<span style="color:blue">Nhân viên</span>';
                            else echo 'Khách hàng';
                        ?>
                    </td>
                    
                    <td>
                        <a href="index.php?act=editkh&id=<?= $kh['user_id'] ?>" class="btn btn-edit">Sửa</a>
                        
                        <a href="index.php?act=deletekh&id=<?= $kh['user_id'] ?>" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản: <?= $kh['username'] ?>?')" 
                           class="btn btn-delete">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Chưa có dữ liệu nào!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>