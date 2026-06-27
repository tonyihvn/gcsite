
<?php
// Include jumbotron component
include __DIR__ . '/../components/jumbotron.php';

// Display blog jumbotron
renderJumbotron(
    'Our Blog',
    'Stay updated with our latest insights and industry news.',
    '',
    '#667eea'
);
?>

<div class="container py-5">
    <div class="row">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <?php if (!empty($post['featured_image'])): ?>
                    <img src="<?= htmlspecialchars(\Core\FileUploader::getImageUrl($post['featured_image'])) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <small class="text-muted"><?= $post['published_at'] ?></small>
                        <h5 class="card-title mt-2"><?= htmlspecialchars($post['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($post['excerpt'] ?? '') ?></p>
                        <a href="<?= route('blog/' . $post['slug']) ?>" class="btn btn-outline-primary btn-sm">Read More</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                No blog posts available yet.
            </div>
        <?php endif; ?>
    </div>
</div>
