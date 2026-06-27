<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Media Library</h3>
    <a href="<?= route('admin/media/upload') ?>" class="btn btn-primary">
        <i class="fas fa-upload"></i> Upload File
    </a>
</div>

<div class="row g-3">
    <?php if (!empty($media)): ?>
        <?php foreach ($media as $file): ?>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file['path'] ?? '')): ?>
                            <img src="<?= $file['path'] ?>" alt="<?= $file['filename'] ?>" style="max-height: 150px; max-width: 100%;" class="mb-2">
                        <?php else: ?>
                            <i class="fas fa-file fa-3x text-muted mb-2"></i>
                        <?php endif; ?>
                        <p class="small mb-2"><?= htmlspecialchars($file['filename'] ?? '') ?></p>
                        <small class="text-muted d-block mb-3"><?= $file['size'] ?? '0 KB' ?></small>
                        <a href="<?= route('admin/media/delete/' . $file['id']) ?>" class="btn btn-sm btn-danger" onclick="confirmDelete('Are you sure you want to delete this file?', 'true', this); return false;">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> No files uploaded yet. <a href="<?= route('admin/media/upload') ?>">Upload files</a>
            </div>
        </div>
    <?php endif; ?>
</div>
