<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($post) ? 'Edit Blog Post' : 'Create New Blog Post' ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('admin/blog' . (isset($post) ? '/' . $post['id'] : '')) ?>" class="row g-3" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label class="form-label">Post Title *</label>
                <input type="text" class="form-control" name="title" value="<?= $post['title'] ?? '' ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">URL Slug *</label>
                <input type="text" class="form-control" name="slug" value="<?= $post['slug'] ?? '' ?>" placeholder="e.g., my-blog-post" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <input type="text" class="form-control" name="category" value="<?= $post['category'] ?? '' ?>" placeholder="e.g., Technology, Business">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="draft" <?= ($post['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
            
            <div class="col-12">
                <label class="form-label">Excerpt</label>
                <textarea class="form-control" name="excerpt" rows="3" placeholder="Brief summary of the post"><?= $post['excerpt'] ?? '' ?></textarea>
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
                        <input type="url" class="form-control" name="featured_image" value="<?= $post['featured_image'] ?? '' ?>" placeholder="https://... (optional alternative)">
                        <small class="text-muted">Leave empty if uploading file</small>
                    </div>
                </div>
                <?php if (isset($post) && !empty($post['featured_image'])): ?>
                    <div class="mt-2">
                        <img src="<?= \Core\FileUploader::getImageUrl($post['featured_image']) ?>" alt="Current image" style="max-height: 100px; border-radius: 4px;">
                        <small class="d-block text-muted mt-1">Current featured image</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-12">
                <label class="form-label">Post Content *</label>
                <textarea class="form-control wyswyg" name="content" rows="12" placeholder="Full blog post content" required><?= $post['content'] ?? '' ?></textarea>
            </div>
            
            <div class="col-12">
                <label class="form-label">Tags</label>
                <input type="text" class="form-control" name="tags" value="<?= $post['tags'] ?? '' ?>" placeholder="Comma-separated tags: tag1, tag2, tag3">
            </div>
            
            <div class="col-12">
                <div class="row gap-2">
                    <button type="submit" class="btn btn-primary col-auto">
                        <i class="fas fa-save"></i> <?= isset($post) ? 'Update Post' : 'Create Post' ?>
                    </button>
                    <a href="<?= route('admin/blog') ?>" class="btn btn-secondary col-auto">Cancel</a>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
