
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>My Subscriptions</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= route('products') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Subscribe to More Products
            </a>
        </div>
    </div>

    <?php if (!empty($subscriptions)): ?>
    <div class="row">
        <?php foreach ($subscriptions as $sub): ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title"><?= htmlspecialchars($sub['product_name'] ?? 'Unknown Product') ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($sub['plan_name'] ?? 'Standard Plan') ?></p>
                        </div>
                        <span class="badge bg-<?= $sub['status'] === 'active' ? 'success' : ($sub['status'] === 'paused' ? 'warning' : 'danger') ?>">
                            <?= ucfirst($sub['status']) ?>
                        </span>
                    </div>

                    <dl class="row mb-3">
                        <dt class="col-sm-6">Monthly Price:</dt>
                        <dd class="col-sm-6"><strong>₦<?= number_format($sub['plan_price'] ?? 0, 2) ?></strong></dd>

                        <dt class="col-sm-6">Billing Cycle:</dt>
                        <dd class="col-sm-6"><?= ucfirst($sub['billing_cycle'] ?? 'N/A') ?></dd>

                        <dt class="col-sm-6">Start Date:</dt>
                        <dd class="col-sm-6"><?= date('M d, Y', strtotime($sub['start_date'])) ?></dd>

                        <dt class="col-sm-6">Renewal Date:</dt>
                        <dd class="col-sm-6">
                            <?php if ($sub['renewal_date']): ?>
                                <?= date('M d, Y', strtotime($sub['renewal_date'])) ?>
                                <?php if ($sub['auto_renew']): ?>
                                    <span class="badge bg-info ms-1">Auto-renew enabled</span>
                                <?php endif; ?>
                            <?php else: ?>
                                One-time subscription
                            <?php endif; ?>
                        </dd>
                    </dl>

                    <div class="d-grid gap-2 d-md-flex">
                        <a href="<?= route('products/' . $sub['product_slug']) ?>" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="fas fa-external-link-alt"></i> View Product
                        </a>
                        <?php if ($sub['status'] === 'active'): ?>
                        <button type="button" class="btn btn-sm btn-outline-warning flex-fill" onclick="pauseSubscription(<?= $sub['id'] ?>)">
                            <i class="fas fa-pause"></i> Pause
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="cancelSubscription(<?= $sub['id'] ?>)">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <h5><i class="fas fa-info-circle"></i> No Subscriptions Yet</h5>
        <p>You don't have any active subscriptions. Browse our products and subscribe now to get started!</p>
        <a href="<?= route('products') ?>" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> Browse Products
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
    function pauseSubscription(subscriptionId) {
        Swal.fire({
            title: 'Pause Subscription?',
            text: 'You can resume this subscription anytime from your dashboard.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, pause it'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= route("") ?>' + 'dashboard/subscriptions/' + subscriptionId + '/pause', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', data.message, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Failed to pause subscription: ' + error, 'error');
                });
            }
        });
    }

    function cancelSubscription(subscriptionId) {
        Swal.fire({
            title: 'Cancel Subscription?',
            text: 'This action cannot be undone. You will lose access to this product.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, cancel it'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= route("") ?>' + 'dashboard/subscriptions/' + subscriptionId + '/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', data.message, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Failed to cancel subscription: ' + error, 'error');
                });
            }
        });
    }
</script>

