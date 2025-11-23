<div>
    <select class="form-select" aria-label="Default select example">
        <option selected>Tất cả</option>
        <option value="Trong nước">Trong nước</option>
        <option value="Ngoài nước">Ngoài nước</option>
    </select>
</div>
<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Category name</th>
                <th scope="col">Description</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tour_cate_list)): ?>
                <?php foreach ($tour_cate_list as $category): ?>
                    <tr>
                        <th scope="row"><?= $category['category_id'] ?></th>
                        <td><?= $category['category_name'] ?></td>
                        <td><?= $category['description'] ?></td>
                        <td>
                            <button class="btn btn-warning">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <p>Không có danh mục tour nào.</p>
            <?php endif; ?>
        </tbody>
    </table>
</div>