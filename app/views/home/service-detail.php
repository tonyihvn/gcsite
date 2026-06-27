
<div class="container py-5">
    <h1 class="mb-4"><?= htmlspecialchars($service['name']) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <?php if (!empty($service['image_url'])): ?>
            <img src="<?= htmlspecialchars(\Core\FileUploader::getImageUrl($service['image_url'])) ?>" class="img-fluid mb-4" alt="<?= htmlspecialchars($service['name']) ?>">
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">About This Service</h5>
                    <p><?= nl2br(htmlspecialchars($service['description'] ?? '')) ?></p>
                    
                    <?php if (!empty($service['detailed_content'])): ?>
                    <hr>
                    <h5 class="mt-4">Details</h5>
                    <div><?= $service['detailed_content'] ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title">Service Details</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Price:</dt>
                        <dd class="col-sm-6">
                            <?php if (!empty($service['base_price'])): ?>
                            ₦<?= number_format($service['base_price'], 2) ?>
                            <?php else: ?>
                            <span class="badge bg-info">Custom</span>
                            <?php endif; ?>
                        </dd>
                        
                        <?php if (!empty($service['delivery_days'])): ?>
                        <dt class="col-sm-6">Delivery:</dt>
                        <dd class="col-sm-6"><?= $service['delivery_days'] ?> days</dd>
                        <?php endif; ?>
                    </dl>

                    <div class="d-grid mt-3">
                        <?php if (is_auth()): ?>
                        <a href="<?= route('dashboard/products') ?>" class="btn btn-primary">Request Service</a>
                        <?php else: ?>
                        <a href="<?= route('auth/login') ?>" class="btn btn-primary">Login to Request</a>
                        <?php endif; ?>

                        <?php if (!empty($service['brochure_url'])): ?>
                        <a href="<?= \Core\FileUploader::getImageUrl($service['brochure_url']) ?>" target="_blank" class="btn btn-outline-info mt-2">
                            <i class="fas fa-file-pdf"></i> Download Brochure
                        </a>
                        <?php endif; ?>

                        <?php if (!empty($service['proposal_url'])): ?>
                        <a href="<?= \Core\FileUploader::getImageUrl($service['proposal_url']) ?>" target="_blank" class="btn btn-outline-info mt-2">
                            <i class="fas fa-file-contract"></i> Download Proposal
                        </a>
                        <?php endif; ?>

                        <a href="<?= route('contact') ?>" class="btn btn-outline-secondary mt-2">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

