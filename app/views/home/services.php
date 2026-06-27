
<?php
// Include jumbotron component
include __DIR__ . '/../components/jumbotron.php';

// Display services jumbotron
renderJumbotron(
    'Our Services',
    'We provide comprehensive IT solutions and consulting services tailored to your business needs.',
    '',
    '#764ba2'
);
?>

<div class="container py-5">
    <div class="row">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <?php if (!empty($service['image_url'])): ?>
                    <img src="<?= htmlspecialchars(\Core\FileUploader::getImageUrl($service['image_url'])) ?>" class="card-img-top" alt="<?= htmlspecialchars($service['name']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($service['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($service['description'] ?? '') ?></p>
                        <?php if (!empty($service['base_price'])): ?>
                        <p class="text-muted mb-2">
                            Starting from: <strong class="text-primary">₦<?= number_format($service['base_price'], 2) ?></strong>
                        </p>
                        <?php endif; ?>
                        <a href="<?= route('services/' . $service['slug']) ?>" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="alert alert-info">
            No services available at the moment.
        </div>
        <?php endif; ?>
    </div>
</div>

