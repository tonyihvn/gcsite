<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Manage Partners</h3>
    <a href="<?= route('admin/partners/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Partner
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Contact Email</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($partners)): ?>
                    <?php foreach ($partners as $partner): ?>
                        <tr>
                            <td><?= $partner['id'] ?? '' ?></td>
                            <td><?= htmlspecialchars($partner['name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($partner['category'] ?? '') ?></td>
                            <td><?= htmlspecialchars($partner['contact_email'] ?? '') ?></td>
                            <td>
                                <span class="badge bg-<?= ($partner['featured'] ?? 0) == 1 ? 'info' : 'secondary' ?>">
                                    <?= ($partner['featured'] ?? 0) == 1 ? 'Yes' : 'No' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= ($partner['status'] ?? '') === 'active' ? 'success' : 'secondary' ?>">
                                    <?= $partner['status'] ?? 'inactive' ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= route('admin/partners/edit/' . $partner['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= route('admin/partners/delete/' . $partner['id']) ?>" class="btn btn-sm btn-danger" onclick="confirmDelete('Are you sure you want to delete this partner?', 'true', this); return false;">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No partners found. <a href="<?= route('admin/partners/create') ?>">Add one</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
