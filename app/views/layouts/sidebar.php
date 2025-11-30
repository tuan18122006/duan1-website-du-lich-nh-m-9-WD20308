<!-- Sidebar Component -->
<aside class="w-64 bg-white h-screen p-6 shadow-inner">
  <nav class="space-y-4 text-lg">
    <a href="#" class="block hover:underline">Dashboard</a>
    <a href="#" class="block hover:underline">Quản lý danh mục Tour</a>

    <!-- Mục cha có submenu -->
    <div class="menu-item">
      <button class="w-full flex justify-between items-center hover:underline focus:outline-none">
        Quản lý điều hành Tour
        <span class="arrow">▾</span>
      </button>
      <!-- Submenu các mục con -->
      <div class="submenu hidden pl-4 mt-2 space-y-2">
        <a href="#" class="block hover:underline">Quản lý danh sách nhân sự</a>
        <a href="#" class="block hover:underline">Quản lý lịch khởi hành & phân bổ nhân sự</a>
        <a href="#" class="block hover:underline">Dịch vụ</a>
        <a href="#" class="block hover:underline">Danh sách khách theo tour</a>
        <a href="#" class="block hover:underline">Check-in</a>
        <a href="#" class="block hover:underline">Phân phòng khách sạn</a>
        <a href="#" class="block hover:underline">Ghi chú đặc biệt</a>
        <a href="#" class="block hover:underline">Nhật ký dịch vụ</a>
      </div>
    </div>
  <br>
    <a href="#" class="block hover:underline">Bán Tour và đặt chỗ</a>
    <a href="#" class="block hover:underline">Quản lý tài khoản</a>
    <a href="#" class="block hover:underline">Báo cáo thống kê</a>
  </nav>
</aside>
<script>
  // Chọn tất cả các nút menu cha
const menuButtons = document.querySelectorAll('.menu-item > button');

menuButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    // toggle class active để CSS hiển thị submenu
    btn.parentElement.classList.toggle('active');
  });
});

</script>