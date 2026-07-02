<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($service) ? 'Edit Service' : 'Create New Service' ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('admin/services' . (isset($service) ? '/' . $service['id'] : '')) ?>" class="row g-3" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label class="form-label">Service Name *</label>
                <input type="text" class="form-control" name="name" value="<?= $service['name'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">URL Slug *</label>
                <input type="text" class="form-control" name="slug" value="<?= $service['slug'] ?? '' ?>" placeholder="e.g., cloud-hosting" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Base Price</label>
                <input type="number" class="form-control" name="base_price" value="<?= $service['base_price'] ?? 0 ?>" min="0" step="0.01" placeholder="0.00">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Delivery Time (days)</label>
                <input type="number" class="form-control" name="delivery_days" value="<?= $service['delivery_days'] ?? 0 ?>" min="0" placeholder="0">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="active" <?= ($service['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($service['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Service Icon (Font Awesome class)</label>
                <input type="text" class="form-control" name="icon" value="<?= $service['icon'] ?? '' ?>" placeholder="e.g., fas fa-cloud, fas fa-shield-alt">
                <small class="text-muted">See <a href="https://fontawesome.com/icons" target="_blank">Font Awesome icons</a></small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Service Website</label>
                <input type="url" class="form-control" name="website" value="<?= $service['website'] ?? '' ?>" placeholder="https://www.service-website.com">
            </div>
            
            <div class="col-12">
                <label class="form-label">Service Image</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label-small">Upload Image File</label>
                        <input type="file" class="form-control" name="image_file" accept="image/*">
                        <small class="text-muted">JPG, PNG, GIF, WebP (Max 5MB)</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-small">Or Select / Enter Image URL</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="image_url" value="<?= htmlspecialchars($service['image_url'] ?? '') ?>" placeholder="Pick from library or paste a URL">
                            <button type="button" class="btn btn-outline-secondary media-browse-btn" data-target="image_url" data-preview="service_image_preview">
                                <i class="fas fa-images"></i> Browse
                            </button>
                        </div>
                        <small class="text-muted">Choose from the media library or upload a new image</small>
                    </div>
                </div>
                <div class="mt-2">
                    <img id="service_image_preview" src="<?= (isset($service) && !empty($service['image_url'])) ? \Core\FileUploader::getImageUrl($service['image_url']) : '' ?>" alt="Selected image" style="max-height: 100px; border-radius: 4px; <?= (isset($service) && !empty($service['image_url'])) ? '' : 'display:none;' ?>">
                    <small class="d-block text-muted mt-1">Selected / current image</small>
                </div>
            </div>
            
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4" placeholder="Brief service description"><?= $service['description'] ?? '' ?></textarea>
            </div>
            
            <div class="col-12">
                <label class="form-label">Detailed Content</label>
                <textarea class="form-control wyswyg" name="detailed_content" rows="8" placeholder="Comprehensive service details with rich text"><?= $service['detailed_content'] ?? '' ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Brochure (PDF/DOC) - For Download</label>
                <input type="file" class="form-control" name="brochure_file" accept=".pdf,.doc,.docx">
                <small class="text-muted">PDF or Word document for users to download</small>
                <?php if (isset($service) && !empty($service['brochure_url'])): ?>
                    <div class="mt-2">
                        <a href="<?= \Core\FileUploader::getImageUrl($service['brochure_url']) ?>" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Download Current
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Proposal (PDF/DOC) - For Download</label>
                <input type="file" class="form-control" name="proposal_file" accept=".pdf,.doc,.docx">
                <small class="text-muted">PDF or Word document for users to download</small>
                <?php if (isset($service) && !empty($service['proposal_url'])): ?>
                    <div class="mt-2">
                        <a href="<?= \Core\FileUploader::getImageUrl($service['proposal_url']) ?>" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Download Current
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-12">
                <div class="row gap-2">
                    <button type="submit" class="btn btn-primary col-auto">
                        <i class="fas fa-save"></i> <?= isset($service) ? 'Update Service' : 'Create Service' ?>
                    </button>
                    <a href="<?= route('admin/services') ?>" class="btn btn-secondary col-auto">Cancel</a>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
