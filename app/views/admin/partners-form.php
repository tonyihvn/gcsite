<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($partner) ? 'Edit Partner' : 'Add New Partner' ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('admin/partners' . (isset($partner) ? '/' . $partner['id'] : '')) ?>" class="row g-3" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" value="<?= $partner['name'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Category *</label>
                <input type="text" class="form-control" name="category" value="<?= $partner['category'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Contact Email *</label>
                <input type="email" class="form-control" name="contact_email" value="<?= $partner['contact_email'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Contact Person</label>
                <input type="text" class="form-control" name="contact_person" value="<?= $partner['contact_person'] ?? '' ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Website URL</label>
                <input type="url" class="form-control" name="website" value="<?= $partner['website'] ?? '' ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Partner Logo</label>
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label-small">Upload Logo File</label>
                        <input type="file" class="form-control" name="logo_file" accept="image/*">
                        <small class="text-muted">JPG, PNG, GIF, WebP (Max 5MB)</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label-small">Or Logo URL</label>
                        <input type="url" class="form-control" name="logo" value="<?= $partner['logo'] ?? '' ?>" placeholder="https://... (optional alternative)">
                        <small class="text-muted">Leave empty if uploading file</small>
                    </div>
                </div>
                <?php if (isset($partner) && !empty($partner['logo'])): ?>
                    <div class="mt-2">
                        <img src="<?= \Core\FileUploader::getImageUrl($partner['logo']) ?>" alt="Current logo" style="max-height: 80px; border-radius: 4px;">
                        <small class="d-block text-muted mt-1">Current partner logo</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Featured</label>
                <select class="form-control" name="featured">
                    <option value="0" <?= ($partner['featured'] ?? 0) == 0 ? 'selected' : '' ?>>No</option>
                    <option value="1" <?= ($partner['featured'] ?? 0) == 1 ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="active" <?= ($partner['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($partner['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="5"><?= $partner['description'] ?? '' ?></textarea>
            </div>
            
            <div class="col-12">
                <div class="row gap-2">
                    <button type="submit" class="btn btn-primary col-auto">
                        <i class="fas fa-save"></i> <?= isset($partner) ? 'Update' : 'Create' ?>
                    </button>
                    <a href="<?= route('admin/partners') ?>" class="btn btn-secondary col-auto">Cancel</a>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
