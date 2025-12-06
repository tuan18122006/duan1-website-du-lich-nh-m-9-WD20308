<style>
    .guide-dashboard {
        padding: 20px;
    }

    /* ============================ */
    /* 1. BỘ LỌC THỜI GIAN */
    /* ============================ */
    .btn-filter-group .btn {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        color: #555;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0.4rem 1rem;
        margin-right: 8px;
        border-radius: 6px !important;
        transition: 0.2s;
    }
    .btn-filter-group .btn.active {
        background-color: #6c757d;
        color: #fff;
        border-color: #6c757d;
        box-shadow: 0 2px 5px rgba(108,117,125,0.3);
    }
    .btn-filter-group .btn:hover:not(.active) {
        background-color: #f8f9fa;
        border-color: #d6d6d6;
    }

    /* ============================ */
    /* 2. CARDS THỐNG KÊ */
    /* ============================ */
    .stat-card {
        background: #fff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.08);
    }

    .icon-box {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-right: 15px;
        flex-shrink: 0;
    }
    .icon-box-primary { background: rgba(13,110,253,0.15); color: #0d6efd; }
    .icon-box-success { background: rgba(25,135,84,0.15); color: #198754; }
    .icon-box-warning { background: rgba(255,193,7,0.15); color: #ffc107; }

    .stat-label {
        font-size: 0.92rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 3px;
    }
    .stat-value {
        font-size: 1.7rem;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 2px;
    }

    /* ============================ */
    /* 3. TABLE TOUR SẮP TỚI */
    /* ============================ */
    .guide-table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .guide-table thead {
        background: #f8f9fa;
        font-weight: 600;
    }

    .guide-table th,
    .guide-table td {
        padding: 12px 16px;
    }

    .badge-ready { background: #198754 !important; }
    .badge-pending { background: #ffc107 !important; color: #000 !important; }
</style>


<div class="guide-dashboard">

    <!-- TITLE -->
    <h4 class="fw-bold mb-4">Dashboard</h4>

    <!-- FILTER BUTTONS -->
    <div class="mb-4 btn-filter-group">
        <button class="btn">Hôm nay</button>
        <button class="btn">Tháng này</button>
        <button class="btn">Năm nay</button>
        <button class="btn active">Tất cả</button>
    </div>


    <!-- 3 CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card card p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box icon-box-primary">
                        <i class="bi bi-map"></i>
                    </div>
                    <div>
                        <div class="stat-label">Tour trong tháng</div>
                        <div class="stat-value">12</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card card p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box icon-box-success">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div>
                        <div class="stat-label">Tổng Booking</div>
                        <div class="stat-value">48</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card card p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box icon-box-warning">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <div class="stat-label">Lịch làm hôm nay</div>
                        <div class="stat-value">2</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- TABLE TOUR -->
    <h5 class="fw-bold mb-3">Tour sắp tới</h5>

    <table class="table guide-table">
        <thead>
            <tr>
                <th>Tên tour</th>
                <th>Ngày bắt đầu</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tour Đà Lạt 3N2Đ</td>
                <td>15/12/2025</td>
                <td><span class="badge badge-ready">Sẵn sàng</span></td>
            </tr>
            <tr>
                <td>Tour Hà Giang 4N3Đ</td>
                <td>20/12/2025</td>
                <td><span class="badge badge-pending">Chuẩn bị</span></td>
            </tr>
        </tbody>
    </table>

</div>
