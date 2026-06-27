<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Manage Slides</h3>
    <a href="<?= route('admin/slides/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Slide
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
                    <th>Image</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($slides)): ?>
                    <?php foreach ($slides as $slide): ?>
                        <tr>
                            <td><?= $slide['id'] ?? '' ?></td>
                            <td><?= htmlspecialchars($slide['title'] ?? '') ?></td>
                            <td><?= substr($slide['description'] ?? '', 0, 50) ?>...</td>
                            <td>
                                <?php if (!empty($slide['image_url'])): ?>
                                    <img src="<?= \Core\FileUploader::getImageUrl($slide['image_url']) ?>" alt="<?= htmlspecialchars($slide['title'] ?? '') ?>" style="height: 40px; width: auto; border-radius: 4px;">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= ($slide['status'] ?? '') === 'active' ? 'success' : 'secondary' ?>">
                                    <?= $slide['status'] ?? 'inactive' ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= route('admin/slides/edit/' . $slide['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= route('admin/slides/delete/' . $slide['id']) ?>" class="btn btn-sm btn-danger" onclick="confirmDelete('Are you sure you want to delete this slide?', 'true', this); return false;">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No slides found. <a href="<?= route('admin/slides/create') ?>">Create one</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
