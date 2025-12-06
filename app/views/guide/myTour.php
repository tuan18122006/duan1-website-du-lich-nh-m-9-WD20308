<h3 class="mb-3">Tour của tôi</h3>

<?php
echo "Guide ID: " . ($_SESSION['user']['guide_id'] ?? 'NULL');
?>
<table class="table table-hover">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Tên tour</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Trạng thái</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($tours as $tour): ?>
            <tr>
                <td><?= $tour['tour_id'] ?></td>
                <td><?= $tour['tour_name'] ?></td>
                <td><?= $tour['start_date'] ?></td>
                <td><?= $tour['end_date'] ?></td>
                <td><?= $tour['status'] == 0 ? 'Đang diễn ra' : 'Chưa khởi hành' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>