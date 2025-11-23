<?php
// Dashboard.php - Trang chính sử dụng include 3 file Header, Sidebar, Footer
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-[#f3f3f3] flex h-screen">

  <!-- SIDEBAR -->
  <div>
    <?php include "sidebar.php"; ?>
  </div>

  <div class="flex-1 flex flex-col">

    <!-- HEADER -->
    <div>
      <?php include "header.php"; ?>
    </div>

    <!-- MAIN CONTENT -->
    <main class="p-6">
      <h1 class="text-3xl font-semibold mb-4">Dashboard</h1>
      <br>
      <hr>
        <br>
      <div class="flex gap-3 mb-6">
        <button class="px-4 py-2 bg-white rounded shadow">Ngày</button>
        <button class="px-4 py-2 bg-white rounded shadow">Tháng</button>
        <button class="px-4 py-2 bg-white rounded shadow">Năm</button>
        <button class="px-4 py-2 bg-white rounded shadow">Tất cả</button>
      </div>
        <br>
      <div class="grid">
  <div>
    <h2>Tổng Tour</h2>
    <p>150</p>
  </div>
  <div>
    <h2>Doanh thu</h2>
    <p>250.090.000VNĐ</p>
  </div>
  <div>
    <h2>Số lượng khách</h2>
    <p>1050</p>
  </div>
</div>



      <div class="bg-white p-6 rounded shadow h-64">
        <p>Biểu đồ doanh thu</p>
        <div class="w-full h-full border border-gray-300 mt-4"></div>
      </div>
    </main>

    
  </div>
</body>
</html>