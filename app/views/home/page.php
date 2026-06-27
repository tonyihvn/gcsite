
<?php
// Include jumbotron component
include __DIR__ . '/../components/jumbotron.php';

// Display jumbotron if header is set
if (!empty($page['page_header'])):
    renderJumbotron(
        $page['page_header'],
        $page['page_subheader'] ?? '',
        !empty($page['header_bg_image']) ? \Core\FileUploader::getImageUrl($page['header_bg_image']) : '',
        $page['header_bg_color'] ?? '#f8f9fa'
    );
endif;
?>

<div class="container py-5">
    <?php if (empty($page['page_header'])): ?>
        <h1 class="mb-4"><?= htmlspecialchars($page['title']) ?></h1>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <?php if (!empty($page['featured_image'])): ?>
            <img src="<?= \Core\FileUploader::getImageUrl($page['featured_image']) ?>" class="img-fluid mb-4" alt="<?= htmlspecialchars($page['title']) ?>">
            <?php endif; ?>

            <div class="page-content">
                <?= $page['content'] ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Page Information</h5>
                    <p class="text-muted">
                        Last updated: <?= $page['updated_at'] ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
