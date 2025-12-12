<div class="container-fluid px-4 mt-4">
    <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-geo-alt me-2"></i>Lịch Trình Của Tôi</h3>

    <?php 
    // KHÔNG SỬ DỤNG $GLOBALS['view_data'] TRỰC TIẾP Ở ĐÂY, SỬ DỤNG CÁC BIẾN ĐƯỢC TRUYỀN VÀO NẾU HỆ THỐNG CỦA BẠN DÙNG EXTRACT()
    // Giả định rằng biến $tours và $GLOBALS['view_data'] là hợp lệ

    // Dùng biến $tours từ Controller (có thể đã được truyền qua extract hoặc $GLOBALS)
    $tours = $tours ?? $GLOBALS['view_data']['tours'] ?? [];
    ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-octagon-fill me-2"></i>
            <?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3">ID Tour</th>
                        <th>Tên Tour</th>
                        <th>Lịch trình</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($tours)): ?>
                        <?php foreach ($tours as $tour):
                            $schedule_id = $tour['schedule_id'] ?? 0;

                            // LOGIC TÍNH TRẠNG THÁI: Giữ nguyên
                            $now_day_start = strtotime(date('Y-m-d'));
                            $start_day_start = strtotime(date('Y-m-d', strtotime($tour['schedule_start'])));

                            $end_of_day_string = date('Y-m-d', strtotime($tour['schedule_end'])) . ' 23:59:59';
                            $end = strtotime($end_of_day_string);

                            $tour_status = '';
                            $status_badge_html = '';

                            if (time() > $end) {
                                $status_badge_html = '<span class="badge bg-secondary">Đã hoàn thành</span>';
                                $tour_status = 'completed';
                            } elseif ($now_day_start < $start_day_start) {
                                $status_badge_html = '<span class="badge bg-warning text-dark">Sắp khởi hành</span>';
                                $tour_status = 'upcoming';
                            } else {
                                $status_badge_html = '<span class="badge bg-success">Đang diễn ra</span>';
                                $tour_status = 'ongoing';
                            }
                        ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#<?= $tour['tour_id'] ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                </td>
                                <td>
                                    <div><i class="bi bi-calendar-event me-1 text-info"></i> Đi: <?= date('d/m/Y H:i', strtotime($tour['schedule_start'])) ?></div>
                                    <div class="small text-muted mt-1"><i class="bi bi-calendar-check me-1"></i> Về: <?= date('d/m/Y', strtotime($tour['schedule_end'])) ?></div>
                                </td>
                                <td>
                                    <?= $status_badge_html ?>
                                </td>

                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <a href="index.php?act=guide_passenger_list&id=<?= $tour['tour_id'] ?>&schedule_id=<?= $schedule_id ?>"
                                            class="btn btn-info btn-sm rounded-pill px-3">
                                            <i class="bi bi-people-fill me-1"></i> Xem khách
                                        </a>

                                        <?php
                                        // LOGIC HIỂN THỊ NÚT GHI NHẬT KÝ
                                        $checkin_button_html = '';
                                        if ($tour_status == 'completed') {
                                            $checkin_button_html = '<button class="btn btn-secondary btn-sm rounded-pill px-3" disabled><i class="bi bi-archive me-1"></i> Đã đóng</button>';
                                        } elseif ($tour_status == 'upcoming') {
                                            $checkin_button_html = '<button class="btn btn-secondary btn-sm rounded-pill px-3" disabled><i class="bi bi-clock-history me-1"></i> Chưa đến ngày Check-in</button>';
                                        } elseif ($tour_status == 'ongoing') {
                                            $checkin_button_html = sprintf(
                                                '<button type="button" class="btn btn-warning btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#checkinModal-%s"><i class="bi bi-journal-plus me-1"></i> Ghi Nhật ký</button>',
                                                $schedule_id
                                            );
                                        }
                                        echo $checkin_button_html;
                                        ?>

                                        <?php if ($tour_status == 'ongoing' && $schedule_id): 
                                            // DÒNG CODE QUAN TRỌNG: Lấy dữ liệu khách hàng từ biến toàn cục
                                            $current_passengers = $GLOBALS['view_data']['passengers_by_schedule'][$schedule_id] ?? [];
                                        ?>
                                            <div class="modal fade" id="checkinModal-<?= $schedule_id ?>" tabindex="-1" aria-labelledby="checkinModalLabel-<?= $schedule_id ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form action="index.php?act=guide/checkin" method="POST">
                                                            <div class="modal-header bg-warning text-dark">
                                                                <h5 class="modal-title fw-bold" id="checkinModalLabel-<?= $schedule_id ?>"><i class="bi bi-journal-plus me-2"></i>Ghi Nhật Ký Tour #<?= $tour['tour_id'] ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="schedule_id" value="<?= $schedule_id ?>">
                                                                <p class="small text-muted">Vui lòng điểm danh và ghi chú lại quá trình diễn ra tour.</p>

                                                                <div class="my-3 border p-3 rounded-3 bg-light">
                                                                    <label class="form-label fw-bold mb-2 text-primary"><i class="bi bi-people-fill me-1"></i> Điểm danh Khách hàng:</label>
                                                                    <div class="list-group list-group-flush">
                                                                        
                                                                        <?php if (!empty($current_passengers)): ?>
                                                                            <?php foreach ($current_passengers as $p): 
                                                                                $role_text = $p['is_booker'] ? 'Trưởng đoàn' : 'Khách kèm';
                                                                                // Sử dụng passenger_id hoặc id (tùy thuộc vào cách bạn đặt tên cột trong bảng passengers)
                                                                                $passenger_id = $p['passenger_id'] ?? $p['id']; 
                                                                            ?>
                                                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                                    <span>
                                                                                        <span class="fw-semibold me-2 text-dark"><?= htmlspecialchars($p['full_name']) ?></span> 
                                                                                        <span class="badge bg-secondary"><?= $role_text ?></span>
                                                                                    </span>
                                                                                    <div class="form-check form-switch">
                                                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                                                            id="checkin-<?= $passenger_id ?>"
                                                                                            name="passenger_ids[]"
                                                                                            value="<?= $passenger_id ?>"
                                                                                            <?= $p['is_present'] ? 'checked' : '' ?>>
                                                                                        <label class="form-check-label small" for="checkin-<?= $passenger_id ?>">Có mặt</label>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        <?php else: ?>
                                                                            <div class="alert alert-info mb-0 small">Chưa có khách đăng ký cho lịch trình này.</div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="note-<?= $schedule_id ?>" class="form-label fw-bold"><i class="bi bi-pencil-square me-1"></i> Ghi Chú/Nhật Ký Tour:</label>
                                                                    <textarea class="form-control" id="note-<?= $schedule_id ?>" name="note" rows="3" placeholder="Ghi lại các sự kiện quan trọng trong tour..."></textarea>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                <button type="submit" class="btn btn-warning"><i class="bi bi-check2-circle me-1"></i> Hoàn tất Check-in</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Bạn chưa được phân công tour nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>