
<div class="container py-5">
    <h1 class="mb-4"><?= htmlspecialchars($post['title']) ?></h1>
    <small class="text-muted">Published on <?= $post['published_at'] ?></small>

    <div class="row mt-4">
        <div class="col-md-8">
            <?php if (!empty($post['featured_image'])): ?>
            <img src="<?= htmlspecialchars(\Core\FileUploader::getImageUrl($post['featured_image'])) ?>" class="img-fluid mb-4" alt="<?= htmlspecialchars($post['title']) ?>">
            <?php endif; ?>

            <div class="post-content">
                <?= $post['content'] ?>
            </div>

            <div class="mt-4">
                <?php if (!empty($post['tags'])): ?>
                <p>
                    <?php foreach (explode(',', $post['tags']) as $tag): ?>
                    <a href="#" class="badge bg-secondary"><?= htmlspecialchars(trim($tag)) ?></a>
                    <?php endforeach; ?>
                </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">About this Post</h5>
                    <?php if (!empty($post['category'])): ?>
                    <p class="mb-2">
                        <strong>Category:</strong> 
                        <span class="badge bg-info"><?= htmlspecialchars($post['category']) ?></span>
                    </p>
                    <?php endif; ?>
                    <p class="mb-0">
                        <strong>Views:</strong> 
                        <?= $post['views_count'] ?? 0 ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
