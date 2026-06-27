
<div class="table-responsive">
    <div class="mb-3">
        <a href="<?= route('admin/products/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Pricing Model</th>
                <th>Base Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['category'] ?? '-') ?></td>
                <td><span class="badge bg-secondary"><?= ucfirst($product['pricing_model']) ?></span></td>
                <td>₦<?= number_format($product['base_price'] ?? 0, 2) ?></td>
                <td><span class="badge bg-<?= $product['status'] === 'published' ? 'success' : 'warning' ?>"><?= ucfirst($product['status']) ?></span></td>
                <td>
                    <a href="<?= route('admin/products/' . $product['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="<?= route('admin/products/' . $product['id'] . '/delete') ?>" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirmFormDelete(this.form, 'Are you sure you want to delete this product?');">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

