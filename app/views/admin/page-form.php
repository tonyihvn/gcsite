<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($page) ? 'Edit Page' : 'Create New Page' ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('admin/pages' . (isset($page) ? '/' . $page['id'] : '')) ?>" class="row g-3" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="col-md-6">
                            <label class="form-label">Page Title *</label>
                            <input type="text" class="form-control" name="title" value="<?= $page['title'] ?? '' ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">URL Slug *</label>
                            <input type="text" class="form-control" name="slug" value="<?= $page['slug'] ?? '' ?>" placeholder="e.g., about-us" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Visibility</label>
                            <select class="form-control" name="visibility">
                                <option value="public" <?= ($page['visibility'] ?? '') === 'public' ? 'selected' : '' ?>>Public</option>
                                <option value="private" <?= ($page['visibility'] ?? '') === 'private' ? 'selected' : '' ?>>Private</option>
                                <option value="password" <?= ($page['visibility'] ?? '') === 'password' ? 'selected' : '' ?>>Password Protected</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Parent Page (Menu Hierarchy)</label>
                            <select class="form-control" name="parent_id">
                                <option value="">-- No Parent (Top Level) --</option>
                                <?php if (!empty($all_pages)): ?>
                                    <?php foreach ($all_pages as $p): ?>
                                        <?php if (!isset($page) || $p['id'] !== $page['id']): ?>
                                            <option value="<?= $p['id'] ?>" 
                                                <?= (isset($page) && $page['parent_id'] == $p['id']) ? 'selected' : '' ?>>
                                                <?= str_repeat('— ', ($p['depth'] ?? 0)) ?><?= htmlspecialchars($p['title']) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Create a submenu under another page</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Menu Order</label>
                            <input type="number" class="form-control" name="menu_order" value="<?= $page['menu_order'] ?? 0 ?>" min="0" step="1">
                            <small class="text-muted">Sort position in menu (0, 1, 2...)</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Page Description</label>
                            <input type="text" class="form-control" name="description" value="<?= $page['description'] ?? '' ?>" placeholder="Brief description for SEO">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Featured Image</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label-small">Upload Image File</label>
                                    <input type="file" class="form-control" name="featured_image_file" accept="image/*">
                                    <small class="text-muted">JPG, PNG, GIF, WebP (Max 5MB)</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-small">Or Image URL</label>
                                    <input type="url" class="form-control" name="featured_image" value="<?= $page['featured_image'] ?? '' ?>" placeholder="https://... (optional alternative)">
                                    <small class="text-muted">Leave empty if uploading file</small>
                                </div>
                            </div>
                            <?php if (isset($page) && !empty($page['featured_image'])): ?>
                                <div class="mt-2">
                                    <img src="<?= \Core\FileUploader::getImageUrl($page['featured_image']) ?>" alt="Current image" style="max-height: 100px; border-radius: 4px;">
                                    <small class="d-block text-muted mt-1">Current featured image</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Page Content *</label>
                            <textarea class="form-control" name="content" rows="10" placeholder="Page content (supports HTML and Markdown)" required><?= $page['content'] ?? '' ?></textarea>
                        </div>

                        <!-- Jumbotron Settings -->
                        <div class="col-12">
                            <div class="card bg-light mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-image"></i> Page Header/Jumbotron Settings (Optional)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Header Title</label>
                                            <input type="text" class="form-control" name="page_header" value="<?= $page['page_header'] ?? '' ?>" placeholder="e.g., About GINTEC Solutions">
                                            <small class="text-muted">Large heading displayed in page header section</small>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Header Subtitle</label>
                                            <input type="text" class="form-control" name="page_subheader" value="<?= $page['page_subheader'] ?? '' ?>" placeholder="e.g., Transforming businesses through innovative IT solutions">
                                            <small class="text-muted">Secondary text displayed under the main header</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Header Background Image</label>
                                            <input type="file" class="form-control" name="header_bg_image_file" accept="image/*">
                                            <small class="text-muted">JPG/PNG recommended (leave empty to use color only)</small>
                                            <?php if (isset($page) && !empty($page['header_bg_image'])): ?>
                                                <div class="mt-2">
                                                    <img src="<?= \Core\FileUploader::getImageUrl($page['header_bg_image']) ?>" alt="Current header BG" style="max-height: 60px; border-radius: 4px;">
                                                    <small class="d-block text-muted">Current header background</small>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Header Background Color</label>
                                            <input type="color" class="form-control form-control-color" name="header_bg_color" value="<?= $page['header_bg_color'] ?? '#f8f9fa' ?>" style="height: 45px;">
                                            <small class="text-muted">Used if no background image provided</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="row gap-2">
                                <button type="submit" class="btn btn-primary col-auto">
                                    <i class="fas fa-save"></i> <?= isset($page) ? 'Update Page' : 'Create Page' ?>
                                </button>
                                <a href="<?= route('admin/pages') ?>" class="btn btn-secondary col-auto">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
