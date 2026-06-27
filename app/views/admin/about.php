<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Manage About Sections</h3>
    <a href="<?= route('admin/about/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Section
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Sort Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sections)): ?>
                    <?php foreach ($sections as $section): ?>
                        <tr>
                            <td><?= $section['id'] ?? '' ?></td>
                            <td><?= htmlspecialchars($section['title'] ?? '') ?></td>
                            <td><?= substr(htmlspecialchars($section['description'] ?? ''), 0, 50) ?>...</td>
                            <td><?= $section['sort_order'] ?? 0 ?></td>
                            <td>
                                <a href="<?= route('admin/about/edit/' . $section['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= route('admin/about/delete/' . $section['id']) ?>" class="btn btn-sm btn-danger" onclick="confirmDelete('Are you sure you want to delete this section?', 'true', this); return false;">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No sections found. <a href="<?= route('admin/about/create') ?>">Add one</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
