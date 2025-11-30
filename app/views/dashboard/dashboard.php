<?php
// File này chỉ chứa nội dung bên trong, được include vào main layout
?>

<!-- Style riêng cho trang Dashboard -->
<style>
      .dashboard-content .row {
        height: auto !important;
    }
    /* 1. BỘ LỌC THỜI GIAN (Giống mẫu ảnh) */
    .dashboard-content .btn-filter-group .btn {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        color: #555;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0.4rem 1rem;
        margin-right: 8px; /* Khoảng cách giữa các nút */
        border-radius: 6px !important; /* Bo góc nhẹ */
        transition: all 0.2s;
    }
    /* Nút đang chọn (Active) - Màu tối như trong ảnh */
    .dashboard-content .btn-filter-group .btn.active {
        background-color: #6c757d; /* Màu xám đậm */
        color: #fff;
        border-color: #6c757d;
        box-shadow: 0 2px 5px rgba(108, 117, 125, 0.3);
    }
    .dashboard-content .btn-filter-group .btn:hover:not(.active) {
        background-color: #f8f9fa;
        border-color: #d6d6d6;
    }

    /* 2. THẺ THỐNG KÊ (STATS CARDS) - Gọn gàng hơn */
    .dashboard-content .stat-card {
        background: #fff;
        border: none;
        border-radius: 12px; /* Bo góc vừa phải */
        box-shadow: 0 2px 6px rgba(0,0,0,0.02); /* Bóng đổ rất nhẹ */
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .dashboard-content .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .dashboard-content .stat-card .card-body {
        padding: 2rem; /* Giảm padding để card đỡ bị 'rộng' */
    }

    /* Icon tròn */
    .dashboard-content .icon-box {
        width: 40px; /* Thu nhỏ size icon */
        height: 40px;
        border-radius: 50%; /* Tròn vo như mẫu */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-right: 15px;
        flex-shrink: 0;
    }

    /* Màu icon (Nhạt hơn cho tinh tế) */
    .icon-box-primary { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .icon-box-success { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .icon-box-warning { background: rgba(255, 193, 7, 0.1); color: #ffc107; }

    /* Typography trong Card */
    .stat-label {
        font-size: 0.9rem;
        color: #6c757d; /* Màu chữ tiêu đề nhạt */
        font-weight: 600;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 1.5rem; /* Số liệu to */
        font-weight: 700;
        color: #343a40;
        line-height: 1.2;
    }
    .stat-note {
        font-size: 0.75rem;
        color: #28a745; /* Màu xanh lá cho % tăng trưởng */
        margin-top: 2px;
        font-weight: 500;
    }

    /* 3. BIỂU ĐỒ */
    .dashboard-content .chart-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        padding: 1.5rem;
    }
</style>

<div class="container-fluid p-0 dashboard-content">
    
    <!-- 1. Header Title -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark m-0">Dashboard</h4>
    </div>

    <!-- 2. Filter Buttons (Nằm riêng 1 hàng giống ảnh) -->
    <div class="mb-4">
        <div class="btn-group btn-filter-group" role="group">
            <button type="button" class="btn">Hôm nay</button>
            <button type="button" class="btn">Tháng này</button>
            <button type="button" class="btn">Năm nay</button>
            <button type="button" class="btn active">Tất cả</button>
        </div>
    </div>

    <!-- 3. Stats Cards Row -->
    <div class="row g-3 mb-4"> <!-- g-3 để khoảng cách giữa các thẻ hẹp hơn -->
        
        <!-- Card 1: Tổng Tour -->
        <div class="col-12 col-md-4">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-box-primary">
                        <i class="bi bi-cart"></i> <!-- Icon giỏ hàng/Tour -->
                    </div>
                    <div>
                        <div class="stat-label">Tổng Tour</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Doanh Thu -->
        <div class="col-12 col-md-4">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-box-success">
                        <i class="bi bi-currency-dollar"></i> <!-- Icon tiền -->
                    </div>
                    <div>
                        <div class="stat-label">Doanh thu</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Khách Hàng -->
        <div class="col-12 col-md-4">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-box-warning">
                        <i class="bi bi-people"></i> <!-- Icon người -->
                    </div>
                    <div>
                        <div class="stat-label">Khách hàng</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Chart Section (Giữ nguyên style nhưng làm gọn lại) -->
    <div class="row">
        <div class="col-12">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold m-0 text-dark">Tất cả</h6>
                </div>
                
                <!-- Placeholder biểu đồ -->
                <div class="w-100 bg-light rounded d-flex align-items-center justify-content-center text-muted" style="height: 300px; border: 1px solid #eee;">
                    <div class="text-center">
                    </div>
                </div>
                <!-- Chú thích biểu đồ -->
                <div class="mt-3 text-center small">
                     <span class="me-3"><i class="bi bi-circle-fill text-primary" style="font-size: 8px;"></i> Doanh thu</span>
                     <span><i class="bi bi-circle-fill text-success" style="font-size: 8px;"></i> Lợi nhuận</span>
                </div>
            </div>
        </div>
    </div>

</div>