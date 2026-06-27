
<?php
// Include jumbotron component
include __DIR__ . '/../components/jumbotron.php';

// Display products jumbotron
renderJumbotron(
    'Our Products',
    'Discover our innovative software solutions designed for your business.',
    '',
    '#667eea'
);
?>

<div class="container py-5">
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($product['image_url'])): ?>
                    <img src="<?= htmlspecialchars(\Core\FileUploader::getImageUrl($product['image_url'])) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-box fa-3x text-muted"></i>
                    </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($product['category'] ?? '') ?></p>
                        <p class="card-text small"><?= substr(htmlspecialchars(strip_tags($product['description'] ?? '')), 0, 100) ?>...</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="badge bg-primary"><?= ucfirst($product['pricing_model']) ?></span>
                                <?php if (!empty($product['base_price'])): ?>
                                <p class="mb-0 mt-2"><strong>₦<?= number_format($product['base_price'], 2) ?></strong></p>
                                <?php endif; ?>
                            </div>
                            <a href="<?= route('products/' . $product['slug']) ?>" class="btn btn-outline-primary btn-sm">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="alert alert-info">
            No products available at the moment.
        </div>
        <?php endif; ?>
    </div>
</div>

