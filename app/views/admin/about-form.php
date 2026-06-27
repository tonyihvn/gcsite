<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($about) ? 'Edit About Section' : 'Add About Section' ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('admin/about' . (isset($about) ? '/' . $about['id'] : '')) ?>" class="row g-3" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label class="form-label">Section Name *</label>
                <input type="text" class="form-control" name="section_name" value="<?= $about['section_name'] ?? '' ?>" required placeholder="e.g., company_story, mission, vision, values">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Title *</label>
                <input type="text" class="form-control" name="title" value="<?= $about['title'] ?? '' ?>" required placeholder="e.g., Our Story">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Sort Order</label>
                <input type="number" class="form-control" name="sort_order" value="<?= $about['sort_order'] ?? 0 ?>">
            </div>
            
            <div class="col-md-12">
                <label class="form-label">Section Image</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label-small">Upload Image File</label>
                        <input type="file" class="form-control" name="image_file" accept="image/*">
                        <small class="text-muted">JPG, PNG, GIF, WebP (Max 5MB)</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-small">Or Image URL</label>
                        <input type="url" class="form-control" name="image" value="<?= $about['image'] ?? '' ?>" placeholder="https://... (optional alternative)">
                        <small class="text-muted">Leave empty if uploading file</small>
                    </div>
                </div>
                <?php if (isset($about) && !empty($about['image'])): ?>
                    <div class="mt-2">
                        <img src="<?= \Core\FileUploader::getImageUrl($about['image']) ?>" alt="Current image" style="max-height: 100px; border-radius: 4px;">
                        <small class="d-block text-muted mt-1">Current section image</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-12">
                <label class="form-label">Content *</label>
                <textarea class="form-control" name="content" rows="10" required><?= $about['content'] ?? '' ?></textarea>
                <small class="text-muted">Markdown is supported</small>
            </div>
            
            <div class="col-12">
                <div class="row gap-2">
                    <button type="submit" class="btn btn-primary col-auto">
                        <i class="fas fa-save"></i> <?= isset($about) ? 'Update' : 'Create' ?>
                    </button>
                    <a href="<?= route('admin/about') ?>" class="btn btn-secondary col-auto">Cancel</a>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
