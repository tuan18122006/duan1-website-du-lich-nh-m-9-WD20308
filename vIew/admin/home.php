<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        /* XOÁ override navbar để tránh vỡ layout */
        .navbar {
            z-index: 1000;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: #343a40;
            position: fixed;
            top: 0;
            padding-top: 80px; /* đẩy xuống dưới navbar */
        }

        .sidebar a {
            padding: 12px 20px;
            color: #ddd;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #495057;
            color: #fff;
        }

        .content {
            margin-left: 240px;
            padding: 100px 20px 20px; /* tránh bị đè bởi navbar */
        }
    </style>
</head>

<body>

    <!-- NAVBAR FIX -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
        <div class="container-fluid">

            <a class="navbar-brand" href="#">ADMIN</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                </ul>

                <!-- FORM CĂN GIỮA + CHỐNG VỠ -->
                <form class="d-flex mx-auto" style="max-width: 400px; width: 100%;">
                    <input class="form-control me-2" type="search" placeholder="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>

            </div>
        </div>
    </nav>

    <!-- SIDEBAR FIX -->
    <div class="sidebar">
        <a href="#">Dashboard</a>
        <a href="#">Tours</a>
        <a href="#">Bookings</a>
        <a href="#">Khách hàng</a>
        <a href="#">Hướng dẫn viên</a>
        <a href="#">Cài đặt</a>
    </div>

    <!-- CONTENT FIX -->
    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard</h2>
            <button class="btn btn-primary">Thêm mới</button>
        </div>
