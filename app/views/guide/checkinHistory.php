<div class="container mt-4">
    <h3 class="fw-bold mb-3">Lịch sử Check-in</h3>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="filterFrom" class="form-label fw-semibold">Từ ngày</label>
            <input type="date" name="from" id="filterFrom" class="form-control" value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="filterTo" class="form-label fw-semibold">Đến ngày</label>
            <input type="date" name="to" id="filterTo" class="form-control" value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="filterTour" class="form-label fw-semibold">Tour</label>
            <input type="text" name="tour" id="filterTour" class="form-control" placeholder="Nhập tên tour..." value="<?= htmlspecialchars($_GET['tour'] ?? '') ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100" type="submit">Lọc</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tour</th>
                            <th>Thời gian Check-in</th>
                            <th>Ghi chú</th> <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($history)): ?>    
                            <?php foreach ($history as $index => $item): 
                                // Chuẩn bị dữ liệu cho Modal
                                $fullName = htmlspecialchars($item['full_name'] ?? 'N/A');
                                $tourName = htmlspecialchars($item['tour_name'] ?? 'N/A');
                                $checkinTime = date("d/m/Y H:i", strtotime($item['checkin_time'] ?? ''));
                                $location = htmlspecialchars($item['location'] ?? 'Không có');
                                // Lấy Ghi chú trực tiếp từ dữ liệu, hoặc để trống nếu không có
                                $note = htmlspecialchars($item['note'] ?? 'Không có ghi chú'); 
                            ?>
                                <tr 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailModal"
                                    data-name="<?= $fullName ?>"
                                    data-tour="<?= $tourName ?>"
                                    data-time="<?= $checkinTime ?>"
                                    data-location="<?= $location ?>"
                                    data-note="<?= $note ?>"
                                    style="cursor: pointer;"
                                >
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $tourName ?></td>
                                    <td><?= $checkinTime ?></td>
                                    <td><?= $note ?></td> <td>
                                        <button class="btn btn-sm btn-info text-white" type="button" aria-label="Xem chi tiết">
                                            Xem
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">
                                    Không có lịch sử check-in nào.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Chi tiết Check-in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Khách hàng:</strong> <span id="detailName"></span></p>
                <p><strong>Tour:</strong> <span id="detailTour"></span></p>
                <p><strong>Thời gian:</strong> <span id="detailTime"></span></p>
                <p><strong>Địa điểm:</strong> <span id="detailLocation"></span></p>
                <p><strong>Ghi chú:</strong> <span id="detailNote"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailModal = document.getElementById('detailModal');
        detailModal.addEventListener('show.bs.modal', function (event) {
            // Lấy nút (hoặc hàng) đã kích hoạt modal
            const button = event.relatedTarget.closest('tr'); 

            // Lấy dữ liệu từ các thuộc tính data-*
            const name = button.getAttribute('data-name');
            const tour = button.getAttribute('data-tour');
            const time = button.getAttribute('data-time');
            const location = button.getAttribute('data-location');
            const note = button.getAttribute('data-note');

            // Cập nhật nội dung Modal
            document.getElementById('detailName').textContent = name;
            document.getElementById('detailTour').textContent = tour;
            document.getElementById('detailTime').textContent = time;
            document.getElementById('detailLocation').textContent = location;
            document.getElementById('detailNote').textContent = note;
        });
    });
</script>