
<div class="container py-5">
    <h1 class="mb-4"><?= htmlspecialchars($product['name']) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <?php if (!empty($product['image_url'])): ?>
            <img src="<?= htmlspecialchars(\Core\FileUploader::getImageUrl($product['image_url'])) ?>" class="img-fluid mb-4" alt="<?= htmlspecialchars($product['name']) ?>">
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Product Overview</h5>
                    <div><?= $product['description'] ?? '' ?></div>
                    
                    <?php if (!empty($product['features'])): ?>
                    <h6 class="mt-4">Key Features</h6>
                    <ul>
                        <?php foreach (explode(',', $product['features']) as $feature): ?>
                        <li><?= htmlspecialchars(trim($feature)) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title">Product Information</h5>
                    <dl class="row mb-3">
                        <dt class="col-sm-6">Category:</dt>
                        <dd class="col-sm-6"><?= htmlspecialchars($product['category'] ?? 'N/A') ?></dd>
                        
                        <dt class="col-sm-6">Pricing:</dt>
                        <dd class="col-sm-6">
                            <span class="badge bg-info"><?= ucfirst($product['pricing_model']) ?></span>
                        </dd>

                        <?php if (!empty($product['base_price'])): ?>
                        <dt class="col-sm-6">Price:</dt>
                        <dd class="col-sm-6"><strong class="text-primary">₦<?= number_format($product['base_price'], 2) ?></strong></dd>
                        <?php endif; ?>
                    </dl>

                    <div class="d-grid">
                        <?php if (!empty($product['demo_url'])): ?>
                        <a href="<?= htmlspecialchars($product['demo_url']) ?>" target="_blank" class="btn btn-outline-info mb-2">
                            Try Demo
                        </a>
                        <?php endif; ?>
                        
                        <?php if (is_auth()): ?>
                        <a href="<?= route('subscribe/' . $product['id']) ?>" class="btn btn-primary mb-2">
                            <i class="fas fa-shopping-cart"></i> Subscribe Now
                        </a>
                        <?php else: ?>
                        <a href="<?= route('auth/login') ?>" class="btn btn-primary mb-2">
                            Login to Subscribe
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($product['documentation_url'])): ?>
                        <a href="<?= htmlspecialchars($product['documentation_url']) ?>" target="_blank" class="btn btn-outline-secondary">
                            Documentation
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($product['brochure_url'])): ?>
                        <a href="<?= \Core\FileUploader::getImageUrl($product['brochure_url']) ?>" target="_blank" class="btn btn-outline-info mt-2">
                            <i class="fas fa-file-pdf"></i> Download Brochure
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($product['proposal_url'])): ?>
                        <a href="<?= \Core\FileUploader::getImageUrl($product['proposal_url']) ?>" target="_blank" class="btn btn-outline-info mt-2">
                            <i class="fas fa-file-contract"></i> Download Proposal
                        </a>
                        <?php endif; ?>
                        
                        <a href="<?= route('contact') ?>" class="btn btn-outline-secondary mt-2">
                            Inquire Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

