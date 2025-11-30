<div class="container mt-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h4 class="mb-0 fw-bold"><i class="fas fa-calendar-plus me-2"></i>Tạo Booking Mới</h4>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="index.php?act=booking_add">
                
                <div class="row">
                    <div class="col-md-6 border-end">
                        <h5 class="text-primary mb-3">1. Thông tin khách hàng</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên (*)</label>
                            <input type="text" name="customer_name" class="form-control" required placeholder="Nhập tên khách hàng...">
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
                            <input type="text" name="customer_address" class="form-control" placeholder="Nhập địa chỉ liên hệ...">
                        </div>
                    </div>

                    <div class="col-md-6 ps-md-4">
                        <h5 class="text-primary mb-3">2. Thông tin Tour</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Tour (*)</label>
                            <select name="tour_id" class="form-select" required onchange="updatePrice(this)">
                                <option value="" data-price="0">-- Chọn Tour du lịch --</option>
                                <?php foreach($tours as $t): ?>
                                    <option value="<?= $t['tour_id'] ?>" data-price="<?= $t['base_price'] ?>">
                                        <?= htmlspecialchars($t['tour_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá vé (VNĐ)</label>
                                <input type="text" id="tourPrice" class="form-control bg-light" readonly value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng người (*)</label>
                                <input type="number" name="people" id="peopleInput" class="form-control" value="1" min="1" required oninput="calculateTotal()">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú thêm</label>
                            <textarea name="note" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="alert alert-info fw-bold text-center">
                            TỔNG TIỀN: <span id="totalDisplay" class="text-danger fs-4">0</span> VNĐ
                        </div>

                        <div class="text-end">
                            <a href="index.php?act=booking_list" class="btn btn-secondary me-2">Hủy bỏ</a>
                            <button type="submit" class="btn btn-success px-4 fw-bold">Xác nhận đặt Tour</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updatePrice(selectElement) {
        var price = selectElement.options[selectElement.selectedIndex].getAttribute('data-price');
        document.getElementById('tourPrice').value = price;
        calculateTotal();
    }

    function calculateTotal() {
        var price = document.getElementById('tourPrice').value;
        var people = document.getElementById('peopleInput').value;
        var total = price * people;
        document.getElementById('totalDisplay').innerText = new Intl.NumberFormat('vi-VN').format(total);
    }
</script>