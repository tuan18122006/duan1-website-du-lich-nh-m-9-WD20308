<div class="container mt-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-success text-white py-3 rounded-top-4">
            <h4 class="mb-0 fw-bold"><i class="fas fa-paint-brush me-2"></i>Thiết kế Tour theo yêu cầu</h4>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="index.php?act=booking_add_custom" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-5 border-end">
                        <h5 class="text-success mb-3">1. Thông tin liên hệ</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ tên (*)</label>
                            <input type="text" name="customer_name" class="form-control" required placeholder="Nhập họ tên...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại (*)</label>
                            <input type="text" name="customer_phone" class="form-control" required placeholder="Nhập số điện thoại...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="customer_email" class="form-control" placeholder="example@email.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <input type="text" name="customer_address" class="form-control" placeholder="Địa chỉ liên hệ...">
                        </div>
                    </div>

                    <div class="col-md-7 ps-md-4">
                        <h5 class="text-success mb-3">2. Ý tưởng chuyến đi của bạn</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bạn muốn đi đâu? (Điểm đến) (*)</label>
                            <input type="text" name="destination" class="form-control" required placeholder="VD: Đà Lạt, Sapa, Nha Trang...">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ngày khởi hành dự kiến (*)</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số ngày đi (*)</label>
                                <input type="number" name="duration_days" class="form-control" min="1" required value="1">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng người (*)</label>
                                <input type="number" name="people" class="form-control" min="1" required value="1">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả chi tiết yêu cầu</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Ví dụ: Tôi muốn ở khách sạn 5 sao, có buffet tối, tham quan Vịnh Hạ Long, ngân sách khoảng 5 triệu/người..."></textarea>
                        </div>

                        <div class="text-end">
                            <a href="index.php" class="btn btn-secondary me-2">Hủy bỏ</a>
                            <button type="submit" class="btn btn-success fw-bold px-4">
                                <i class="fas fa-paper-plane me-2"></i> Gửi yêu cầu
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>