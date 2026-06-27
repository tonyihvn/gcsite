
<div class="container py-5">
    <h1 class="mb-4">Browse Products</h1>

    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text text-muted small"><?= htmlspecialchars($product['category'] ?? '') ?></p>
                        <p class="card-text"><?= substr(htmlspecialchars($product['description'] ?? ''), 0, 100) ?>...</p>
                        
                        <?php if (!empty($product['base_price'])): ?>
                        <p class="mb-0">
                            <strong class="text-primary">₦<?= number_format($product['base_price'], 2) ?></strong>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="<?= route('products/' . $product['slug']) ?>" class="btn btn-primary w-100 btn-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="alert alert-info w-100">
            No products available.
        </div>
        <?php endif; ?>
    </div>
</div>
?>
