<div class="container mt-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h4 class="mb-0 fw-bold"><i class="fas fa-calendar-plus me-2"></i>Tạo Booking Mới</h4>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="index.php?act=booking_add">
                
                <div class="row">
                    <div class="col-md-5 border-end">
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

                    <div class="col-md-7 ps-md-4">
                        <h5 class="text-primary mb-3">2. Chọn Tour & Lịch trình</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Tour (*)</label>
                            <select name="tour_id" id="tourSelect" class="form-select" required onchange="loadSchedules(this.value)">
                                <option value="">-- Chọn Tour du lịch --</option>
                                <?php foreach($tours as $t): ?>
                                    <option value="<?= $t['tour_id'] ?>">
                                        <?= htmlspecialchars($t['tour_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="scheduleTableContainer" class="mb-3" style="display:none;">
                            <label class="form-label fw-bold text-success">Lịch trình hiện có:</label>
                            <div class="table-responsive border rounded bg-light p-2" style="max-height: 200px; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0" style="font-size: 0.9rem;">
                                    <thead class="table-success">
                                        <tr>
                                            <th>Ngày đi</th>
                                            <th>Ngày về</th>
                                            <th>Giá vé</th>
                                            <th>Còn lại</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scheduleTableBody">
                                        </tbody>
                                </table>
                            </div>
                            <small class="text-muted fst-italic">* Tham khảo bảng trên và chọn ngày bên dưới</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Ngày Khởi Hành (*)</label>
                            <select name="schedule_id" id="scheduleSelect" class="form-select" required onchange="updatePriceFromSchedule()">
                                <option value="" data-price="0">-- Vui lòng chọn Tour trước --</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá vé (VNĐ)</label>
                                <input type="text" id="tourPrice" class="form-control bg-light" readonly value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng người (*)</label>
                                <input type="number" name="people" id="peopleInput" class="form-control" value="1" min="1" required oninput="calculateTotal(); generatePassengerForms()">
                            </div>
                            <div class="col-12 mt-3">
                                <h6 class="text-primary fw-bold border-bottom pb-2">Thông tin hành khách tham gia:</h6>
                                <div id="passengerList" class="row g-3">
                                    </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú thêm</label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="alert alert-info fw-bold text-center py-2">
                            TỔNG TIỀN: <span id="totalDisplay" class="text-danger fs-4">0</span> VNĐ
                        </div>

                        <div class="text-end">
                            <a href="index.php?act=booking_list" class="btn btn-secondary me-2">Hủy bỏ</a>
                            <button type="submit" class="btn btn-success px-4 fw-bold">Xác nhận đặt</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. Hàm gọi API lấy danh sách lịch khi chọn Tour
    function loadSchedules(tourId) {
        const scheduleContainer = document.getElementById('scheduleTableContainer');
        const tableBody = document.getElementById('scheduleTableBody');
        const selectBox = document.getElementById('scheduleSelect');
        const priceInput = document.getElementById('tourPrice');

        if(!tourId) {
            selectBox.innerHTML = '<option value="" data-price="0">-- Vui lòng chọn Tour trước --</option>';
            scheduleContainer.style.display = 'none';
            priceInput.value = 0;
            calculateTotal();
            return;
        }
        
        selectBox.innerHTML = '<option>Đang tải dữ liệu...</option>';

        // Gọi AJAX
        fetch('index.php?act=booking_add&ajax_tour_id=' + tourId)
            .then(response => response.json())
            .then(data => {
                let optionsHtml = '<option value="" data-price="0">-- Chọn ngày đi --</option>';
                let tableHtml = '';
                
                if (data.length > 0) {
                    scheduleContainer.style.display = 'block'; // Hiện bảng

                    data.forEach(item => {
                        let remaining = item.stock - item.booked;
                        let dateStart = new Date(item.start_date).toLocaleDateString('vi-VN');
                        let dateEnd = new Date(item.end_date).toLocaleDateString('vi-VN');
                        let priceFmt = new Intl.NumberFormat('vi-VN').format(item.price);

                        // 1. Tạo dòng trong bảng hiển thị
                        tableHtml += `
                            <tr>
                                <td class="fw-bold text-primary">${dateStart}</td>
                                <td>${dateEnd}</td>
                                <td class="text-danger">${priceFmt} đ</td>
                                <td>${remaining} / ${item.stock}</td>
                            </tr>
                        `;

                        // 2. Tạo option trong dropdown
                        optionsHtml += `<option value="${item.schedule_id}" data-price="${item.price}">
                                    Ngày ${dateStart} - Giá: ${priceFmt} đ (Còn ${remaining} chỗ)
                                 </option>`;
                    });
                } else {
                    scheduleContainer.style.display = 'none'; // Ẩn bảng nếu không có lịch
                    optionsHtml = '<option value="" data-price="0">-- Tour này chưa có lịch --</option>';
                }

                tableBody.innerHTML = tableHtml;
                selectBox.innerHTML = optionsHtml;
                priceInput.value = 0;
                calculateTotal();
            })
            .catch(error => {
                console.error('Lỗi:', error);
                selectBox.innerHTML = '<option>Lỗi tải dữ liệu</option>';
            });
    }

    // 2. Cập nhật giá khi chọn dropdown
    function updatePriceFromSchedule() {
        let select = document.getElementById('scheduleSelect');
        let price = select.options[select.selectedIndex].getAttribute('data-price');
        document.getElementById('tourPrice').value = price ? price : 0;
        calculateTotal();
    }

    // 3. Tính tổng tiền
    function calculateTotal() {
        let price = parseInt(document.getElementById('tourPrice').value) || 0;
        let people = parseInt(document.getElementById('peopleInput').value) || 1;
        let total = price * people;
        document.getElementById('totalDisplay').innerText = new Intl.NumberFormat('vi-VN').format(total);
    }
        // Hàm tạo form nhập thông tin người đi kèm
function generatePassengerForms() {
    let count = document.getElementById('peopleInput').value;
    let container = document.getElementById('passengerList');
    
    container.innerHTML = '';

    // LOGIC ĐÚNG:
    // Người số 1 là "Thông tin khách hàng" bên trái.
    // Form này chỉ sinh ra cho người thứ 2 trở đi.
    // Ví dụ: Nhập 1 -> Không hiện form nào. Nhập 2 -> Hiện 1 form.

    if (count <= 1) {
        container.innerHTML = '<div class="col-12 text-muted fst-italic small">Khách hàng chính là người đặt tour.</div>';
        return;
    }

    // Chạy từ 1 đến < count (Tức là bỏ qua người số 0 - người đặt)
    for (let i = 1; i < count; i++) {
        let html = `
            <div class="col-md-6">
                <div class="card bg-light border-0 shadow-sm mb-3">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between">
                            <strong class="text-primary small">Hành khách #${i + 1}</strong>
                        </div>
                        
                        <div class="mt-2 mb-2">
                            <input type="text" name="passengers[${i}][name]" 
                                   class="form-control form-control-sm" 
                                   placeholder="Họ tên khách" required>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="passengers[${i}][age]" 
                                       class="form-control form-control-sm" 
                                       placeholder="Tuổi" min="1">
                            </div>
                            <div class="col-6">
                                <select name="passengers[${i}][gender]" class="form-select form-select-sm">
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
}
</script>