<div class="container mt-4">
    <h3 class="fw-bold mb-3">Lịch sử Check-in</h3>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="act" value="checkin_history">
                
                <div class="col-md-5">
                    <label class="form-label fw-bold text-secondary small">Từ khóa tìm kiếm</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="keyword" class="form-control" 
                               placeholder="Tên tour" 
                               value="<?= htmlspecialchars($GLOBALS['view_data']['keyword'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">Ngày Check-in</label>
                    <input type="date" name="date" class="form-control" 
                           value="<?= htmlspecialchars($GLOBALS['view_data']['date'] ?? '') ?>">
                </div>
                
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-bold flex-grow-1">
                        Lọc dữ liệu
                    </button>
                    <a href="index.php?act=checkin_history" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tour</th>
                            <th>Thời gian Check-in</th>
                            <th>Ghi chú</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($history)): ?>    
                            <?php foreach ($history as $index => $item): 
                                // Chuẩn bị dữ liệu (Đã xóa fullName và location thừa)
                                $tourName = htmlspecialchars($item['tour_name'] ?? 'N/A');
                                $checkinTime = date("d/m/Y H:i", strtotime($item['checkin_time'] ?? ''));
                                $note = htmlspecialchars($item['note'] ?? 'Không có ghi chú'); 
                            ?>
                                <tr 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailModal"
                                    data-tour="<?= $tourName ?>"
                                    data-time="<?= $checkinTime ?>"
                                    data-note="<?= $note ?>"
                                    style="cursor: pointer;"
                                >
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $tourName ?></td>
                                    <td><?= $checkinTime ?></td>
                                    <td><?= $note ?></td> 
                                    <td>
                                        <button class="btn btn-sm btn-info text-white" type="button">
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

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết Check-in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Tour:</strong> <span id="detailTour" class="text-primary fw-bold"></span></p>
                <p><strong>Thời gian:</strong> <span id="detailTime"></span></p>
                <p><strong>Ghi chú:</strong> <span id="detailNote" class="fst-italic text-muted"></span></p>
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
            let button = event.relatedTarget;
            // Đảm bảo lấy đúng thẻ TR nếu người dùng click vào nút con bên trong
            if (button.tagName !== 'TR') {
                button = button.closest('tr');
            }

            // Lấy dữ liệu từ các thuộc tính data-*
            const tour = button.getAttribute('data-tour');
            const time = button.getAttribute('data-time');
            const note = button.getAttribute('data-note');

            // Cập nhật nội dung Modal
            document.getElementById('detailTour').textContent = tour;
            document.getElementById('detailTime').textContent = time;
            document.getElementById('detailNote').textContent = note;
        });
    });
</script>