<?php
// File: app/views/dashboard/dashboard.php

// 1. Xử lý Logic hiển thị tiêu đề và trạng thái nút bấm dựa trên URL
$filter = $_GET['filter'] ?? 'year'; // Mặc định là năm
$date_selected = $_GET['date'] ?? date('Y-m-d');

// Tạo tiêu đề động cho biểu đồ
$chart_title = "Năm " . date('Y');
if ($filter == 'today') $chart_title = "Hôm nay (" . date('d/m/Y') . ")";
if ($filter == 'month') $chart_title = "Tháng " . date('m/Y');
if ($filter == 'custom') $chart_title = "Ngày " . date('d/m/Y', strtotime($date_selected));

// Xử lý dữ liệu mặc định để tránh lỗi nếu Controller chưa truyền sang
$total_tours = $total_tours ?? 0;
$total_revenue = $total_revenue ?? 0;
$total_customers = $total_customers ?? 0;
$chart_data_json = $chart_data_json ?? '[]';
$chart_labels = $chart_labels ?? ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* CSS Tùy chỉnh cho Dashboard */
    .dashboard-content .row { height: auto !important; }
    
    /* Style cho nhóm nút lọc */
    .dashboard-content .btn-filter-group .btn { background-color: #fff; border: 1px solid #e0e0e0; color: #555; font-size: 0.9rem; font-weight: 500; padding: 0.4rem 1rem; margin-right: 8px; border-radius: 6px !important; transition: all 0.2s; }
    .dashboard-content .btn-filter-group .btn.active { background-color: #6c757d; color: #fff; border-color: #6c757d; box-shadow: 0 2px 5px rgba(108, 117, 125, 0.3); }
    .dashboard-content .btn-filter-group .btn:hover:not(.active) { background-color: #f8f9fa; border-color: #d6d6d6; }
    
    /* Style cho thẻ thống kê (Card) */
    .dashboard-content .stat-card { background: #fff; border: none; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s; height: 100%; }
    .dashboard-content .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .dashboard-content .stat-card .card-body { padding: 2rem; }
    
    /* Style cho Icon trong thẻ */
    .dashboard-content .icon-box { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-right: 15px; flex-shrink: 0; }
    .icon-box-primary { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .icon-box-success { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .icon-box-warning { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
    
    /* Chữ số thống kê */
    .stat-label { font-size: 0.9rem; color: #6c757d; font-weight: 600; margin-bottom: 4px; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: #343a40; line-height: 1.2; }
    
    /* Khung chứa biểu đồ */
    .dashboard-content .chart-container { background: #fff; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); padding: 1.5rem; }
</style>

<div class="container-fluid p-0 dashboard-content">
    
    <div class="mb-4">
        <h4 class="fw-bold text-dark m-0">Dashboard</h4>
    </div>

    <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="btn-group btn-filter-group" role="group">
            <a href="index.php?act=dashboard&time=today" class="btn">Hôm nay</a>
            <a href="index.php?act=dashboard&time=this_month" class="btn">Tháng này</a>
            <a href="?filter=year" class="btn <?= $filter == 'year' ? 'active' : '' ?>">Năm nay</a>
        </div>

        <div class="d-flex align-items-center">
            <label for="datePicker" class="me-2 fw-bold text-secondary" style="font-size: 0.9rem;">Chọn ngày:</label>
            <input type="date" id="datePicker" class="form-control" 
                   style="width: auto; border-radius: 6px; border: 1px solid #e0e0e0;"
                   value="<?= $date_selected ?>" 
                   onchange="filterByDate(this.value)">
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-box-primary">
                        <i class="bi bi-map"></i>
                    </div>
                    <div>
                        <div class="stat-label">Tổng số Tour</div>
                        <div class="stat-value"><?= $total_tours ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-box-success">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <div class="stat-label">Tổng doanh thu</div>
                        <div class="stat-value text-success">
                            <?= number_format($total_revenue) ?> đ
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-box-warning">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="stat-label">Khách đã phục vụ</div>
                        <div class="stat-value"><?= number_format($total_customers) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold m-0 text-dark">Biểu đồ doanh thu - <?= $chart_title ?></h6>
                </div>
                
                <div class="w-100" style="height: 350px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Hàm xử lý khi chọn ngày từ DatePicker
    function filterByDate(date) {
        if (date) {
            // QUAN TRỌNG: Phải có act=dashboard và dùng biến time=custom
            window.location.href = `index.php?act=dashboard&time=custom&date=${date}`;
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Lấy dữ liệu từ PHP
        const chartData = <?= $chart_data_json ?>; 
        const chartLabels = <?= json_encode($chart_labels) ?>;

        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Tạo hiệu ứng gradient màu xanh
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(13, 110, 253, 0.5)');
        gradient.addColorStop(1, 'rgba(13, 110, 253, 0.0)');

        // Vẽ biểu đồ
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: chartData,
                    borderColor: '#0d6efd',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0d6efd',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' },
                        ticks: {
                            callback: function(value) {
                                // Định dạng số tiền trục Y (ví dụ: 1000 -> 1.000)
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>