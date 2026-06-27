
<div class="table-responsive">
    <div class="mb-3">
        <a href="<?= route('admin/services/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Service
        </a>
    </div>

    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Base Price</th>
                <th>Delivery Days</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
            <tr>
                <td><?= htmlspecialchars($service['name']) ?></td>
                <td>₦<?= number_format($service['base_price'] ?? 0, 2) ?></td>
                <td><?= $service['delivery_days'] ?? '-' ?> days</td>
                <td><span class="badge bg-<?= $service['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($service['status']) ?></span></td>
                <td>
                    <a href="<?= route('admin/services/' . $service['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="<?= route('admin/services/' . $service['id'] . '/delete') ?>" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirmFormDelete(this.form, 'Are you sure you want to delete this service?');">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

