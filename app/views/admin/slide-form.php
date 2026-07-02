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
                        <label class="form-label-small">Or Select / Enter Image URL</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="image_url" value="<?= htmlspecialchars($slide['image_url'] ?? '') ?>" placeholder="Pick from library or paste a URL">
                            <button type="button" class="btn btn-outline-secondary media-browse-btn" data-target="image_url" data-preview="slide_image_preview">
                                <i class="fas fa-images"></i> Browse
                            </button>
                        </div>
                        <small class="text-muted">Choose from the media library or upload a new image</small>
                    </div>
                </div>
                <div class="mt-2">
                    <img id="slide_image_preview" src="<?= (isset($slide) && !empty($slide['image_url'])) ? \Core\FileUploader::getImageUrl($slide['image_url']) : '' ?>" alt="Selected image" style="max-height: 100px; border-radius: 4px; <?= (isset($slide) && !empty($slide['image_url'])) ? '' : 'display:none;' ?>">
                    <small class="d-block text-muted mt-1">Selected / current image</small>
                </div>
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
