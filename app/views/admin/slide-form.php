<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($slide) ? 'Edit Slide' : 'Create New Slide' ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('admin/slides' . (isset($slide) ? '/' . $slide['id'] : '')) ?>" class="row g-3" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label class="form-label">Slide Title *</label>
                <input type="text" class="form-control" name="title" value="<?= $slide['title'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Sort Order</label>
                <input type="number" class="form-control" name="sort_order" value="<?= $slide['sort_order'] ?? 0 ?>">
            </div>
            
            <div class="col-12">
                <label class="form-label">Slide Description</label>
                <textarea class="form-control" name="description" rows="3"><?= $slide['description'] ?? '' ?></textarea>
            </div>
            
            <div class="col-12">
                <label class="form-label">Slide Image</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label-small">Upload Image File</label>
                        <input type="file" class="form-control" name="image_file" accept="image/*">
                        <small class="text-muted">JPG, PNG, GIF, WebP (Max 5MB)</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-small">Or Image URL</label>
                        <input type="url" class="form-control" name="image_url" value="<?= $slide['image_url'] ?? '' ?>" placeholder="https://... (optional alternative)">
                        <small class="text-muted">Leave empty if uploading file</small>
                    </div>
                </div>
                <?php if (isset($slide) && !empty($slide['image_url'])): ?>
                    <div class="mt-2">
                        <img src="<?= \Core\FileUploader::getImageUrl($slide['image_url']) ?>" alt="Current image" style="max-height: 100px; border-radius: 4px;">
                        <small class="d-block text-muted mt-1">Current image</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Link URL</label>
                <input type="url" class="form-control" name="link_url" value="<?= $slide['link_url'] ?? '' ?>" placeholder="https://...">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Button Text</label>
                <input type="text" class="form-control" name="button_text" value="<?= $slide['button_text'] ?? '' ?>" placeholder="e.g., Learn More">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="active" <?= ($slide['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($slide['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            
            <div class="col-12">
                <div class="row gap-2">
                    <button type="submit" class="btn btn-primary col-auto">
                        <i class="fas fa-save"></i> <?= isset($slide) ? 'Update Slide' : 'Create Slide' ?>
                    </button>
                    <a href="<?= route('admin/slides') ?>" class="btn btn-secondary col-auto">Cancel</a>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
