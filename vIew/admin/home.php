<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Lý</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: #343a40;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #ffffff;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h5 class="text-light text-center mb-4">Admin Panel</h5>
        <a href="#">Dashboard</a>
        <a href="#">Quản lý Tour</a>
        <a href="#">Quản lý Booking</a>
        <a href="#">Quản lý Khách hàng</a>
        <a href="#">Quản lý Hướng dẫn viên</a>
        <a href="#">Cài đặt</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">

        <!-- Navbar -->
        <nav class="navbar navbar-light bg-white shadow-sm rounded mb-4 p-3">
            <span class="navbar-brand mb-0 h4">Quản lý Tour</span>
            <button class="btn btn-primary">Thêm mới</button>
        </nav>

        <!-- Table card -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                Danh sách Tour
            </div>

            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tên tour</th>
                            <th>Giá</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Tour Đà Lạt 3N2Đ</td>
                            <td>3,500,000</td>
                            <td>3 ngày</td>
                            <td><span class="badge bg-success">Đang mở</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-warning">Sửa</button>
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>Tour Phú Quốc 4N3Đ</td>
                            <td>6,200,000</td>
                            <td>4 ngày</td>
                            <td><span class="badge bg-danger">Đã đóng</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-warning">Sửa</button>
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
