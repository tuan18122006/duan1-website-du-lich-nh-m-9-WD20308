<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <h4 class="fw-bold text-primary mb-0">Danh sách đoàn khách</h4>
            <p class="text-muted small mb-0"><?= htmlspecialchars($tour['tour_name']) ?></p>
        </div>
        
        <div>
            <button class="btn btn-primary btn-sm">
                <i class="bi bi-printer"></i> In DS
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6">
            <div class="p-3 bg-white rounded shadow-sm border-start border-4 border-primary">
                <small class="text-muted d-block">Tổng khách</small>
                <span class="fs-4 fw-bold"><?= count($passengers) ?></span> <span class="small">người</span>
            </div>
        </div>
        <div class="col-6">
            <div class="p-3 bg-white rounded shadow-sm border-start border-4 border-success">
                <small class="text-muted d-block">Khởi hành</small>
                <span class="fs-5 fw-bold"><?= isset($passengers[0]['start_date']) ? date('d/m', strtotime($passengers[0]['start_date'])) : '...' ?></span>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Họ tên</th>
                            <th>Thông tin</th>
                            <th>Liên hệ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($passengers)): ?>
                            <?php foreach ($passengers as $index => $p): ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $index + 1 ?></td>
                                
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($p['full_name']) ?></div>
                                    <?php if(empty($p['booker_name'])): ?>
                                        <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Trưởng đoàn</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <div class="small">
                                        <i class="bi bi-person"></i> <?= $p['gender'] ?> 
                                        <span class="text-muted mx-1">|</span> 
                                        <?= $p['age'] ? $p['age'] . ' tuổi' : '?? tuổi' ?>
                                    </div>
                                </td>

                                <td>
                                    <?php if (!empty($p['customer_phone'])): ?>
                                        <a class="btn btn-outline-success btn-sm rounded-pill px-3">
                                            <i class="bi bi-telephone-fill"></i> Gọi
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">---</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-4 text-muted">Chưa có khách nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info mt-3 small">
        <i class="bi bi-info-circle me-1"></i> Lưu ý: Hãy kiểm tra danh sách trước khi khởi hành 30 phút.
    </div>
</div>