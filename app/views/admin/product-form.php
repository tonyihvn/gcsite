<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($product) ? 'Edit Product' : 'Create New Product' ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('admin/products' . (isset($product) ? '/' . $product['id'] : '')) ?>" class="row g-3" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label class="form-label">Product Name *</label>
                <input type="text" class="form-control" name="name" value="<?= $product['name'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">URL Slug *</label>
                <input type="text" class="form-control" name="slug" value="<?= $product['slug'] ?? '' ?>" placeholder="e.g., autoserve-erp" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <input type="text" class="form-control" name="category" value="<?= $product['category'] ?? '' ?>" placeholder="e.g., ERP, CRM, Management">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Pricing Model *</label>
                <select class="form-control" name="pricing_model" required>
                    <option value="" disabled selected>Select pricing model</option>
                    <option value="one-time" <?= ($product['pricing_model'] ?? '') === 'one-time' ? 'selected' : '' ?>>One-Time Purchase</option>
                    <option value="subscription" <?= ($product['pricing_model'] ?? '') === 'subscription' ? 'selected' : '' ?>>Subscription</option>
                    <option value="freemium" <?= ($product['pricing_model'] ?? '') === 'freemium' ? 'selected' : '' ?>>Freemium</option>
                    <option value="custom" <?= ($product['pricing_model'] ?? '') === 'custom' ? 'selected' : '' ?>>Custom Quote</option>
                </select>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Base Price</label>
                <input type="number" class="form-control" name="base_price" value="<?= $product['base_price'] ?? 0 ?>" min="0" step="0.01" placeholder="0.00">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="draft" <?= ($product['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= ($product['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Product Icon (Font Awesome class)</label>
                <input type="text" class="form-control" name="icon" value="<?= $product['icon'] ?? '' ?>" placeholder="e.g., fas fa-cube, fas fa-rocket">
                <small class="text-muted">See <a href="https://fontawesome.com/icons" target="_blank">Font Awesome icons</a></small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Product Website</label>
                <input type="url" class="form-control" name="website" value="<?= $product['website'] ?? '' ?>" placeholder="https://www.product-website.com">
            </div>

            <div class="col-md-6">
                <label class="form-label">Demo URL</label>
                <input type="url" class="form-control" name="demo_url" value="<?= $product['demo_url'] ?? '' ?>" placeholder="https://demo.product.com">
            </div>

            <div class="col-md-6">
                <label class="form-label">Documentation URL</label>
                <input type="url" class="form-control" name="documentation_url" value="<?= $product['documentation_url'] ?? '' ?>" placeholder="https://docs.product.com">
            </div>
            
            <div class="col-12">
                <label class="form-label">Product Image</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label-small">Upload Image File</label>
                        <input type="file" class="form-control" name="image_file" accept="image/*">
                        <small class="text-muted">JPG, PNG, GIF, WebP (Max 5MB)</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-small">Or Select / Enter Image URL</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="image_url" value="<?= htmlspecialchars($product['image_url'] ?? '') ?>" placeholder="Pick from library or paste a URL">
                            <button type="button" class="btn btn-outline-secondary media-browse-btn" data-target="image_url" data-preview="product_image_preview">
                                <i class="fas fa-images"></i> Browse
                            </button>
                        </div>
                        <small class="text-muted">Choose from the media library or upload a new image</small>
                    </div>
                </div>
                <div class="mt-2">
                    <img id="product_image_preview" src="<?= (isset($product) && !empty($product['image_url'])) ? \Core\FileUploader::getImageUrl($product['image_url']) : '' ?>" alt="Selected image" style="max-height: 100px; border-radius: 4px; <?= (isset($product) && !empty($product['image_url'])) ? '' : 'display:none;' ?>">
                    <small class="d-block text-muted mt-1">Selected / current image</small>
                </div>
            </div>
            
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control wyswyg" name="description" rows="6" placeholder="Product description with rich text"><?= $product['description'] ?? '' ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Brochure (PDF/DOC) - For Download</label>
                <input type="file" class="form-control" name="brochure_file" accept=".pdf,.doc,.docx">
                <small class="text-muted">PDF or Word document for users to download</small>
                <?php if (isset($product) && !empty($product['brochure_url'])): ?>
                    <div class="mt-2">
                        <a href="<?= \Core\FileUploader::getImageUrl($product['brochure_url']) ?>" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Download Current
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Proposal (PDF/DOC) - For Download</label>
                <input type="file" class="form-control" name="proposal_file" accept=".pdf,.doc,.docx">
                <small class="text-muted">PDF or Word document for users to download</small>
                <?php if (isset($product) && !empty($product['proposal_url'])): ?>
                    <div class="mt-2">
                        <a href="<?= \Core\FileUploader::getImageUrl($product['proposal_url']) ?>" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Download Current
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-12">
                <label class="form-label">Key Features</label>
                <textarea class="form-control" name="features" rows="6" placeholder="Enter features (one per line or comma-separated)"><?= $product['features'] ?? '' ?></textarea>
            </div>
            
            <div class="col-12">
                <div class="row gap-2">
                    <button type="submit" class="btn btn-primary col-auto">
                        <i class="fas fa-save"></i> <?= isset($product) ? 'Update Product' : 'Create Product' ?>
                    </button>
                    <a href="<?= route('admin/products') ?>" class="btn btn-secondary col-auto">Cancel</a>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
