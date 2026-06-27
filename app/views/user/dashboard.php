
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Dashboard</h5>
                    <p class="text-muted">Welcome back, <?= auth()['first_name'] ?? 'User' ?>!</p>
                </div>
            </div>

            <div class="list-group">
                <a href="<?= route('dashboard') ?>" class="list-group-item list-group-item-action active">
                    <i class="fas fa-chart-line"></i> Overview
                </a>
                <a href="<?= route('dashboard/profile') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="<?= route('dashboard/subscriptions') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-credit-card"></i> Subscriptions
                </a>
                <a href="<?= route('dashboard/invoices') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-receipt"></i> Invoices
                </a>
                <a href="<?= route('dashboard/payments') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-money-bill"></i> Payments
                </a>
                <a href="<?= route('dashboard/products') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-box"></i> Browse Products
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-primary"><?= $active_count ?></h3>
                            <p class="text-muted">Active Subscriptions</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-success">₦<?= number_format($total_spent, 2) ?></h3>
                            <p class="text-muted">Total Spent</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-info"><?= count($invoices) ?></h3>
                            <p class="text-muted">Total Invoices</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Subscriptions</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($subscriptions)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Plan</th>
                                        <th>Status</th>
                                        <th>Renewal Date</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($subscriptions, 0, 5) as $sub): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($sub['product_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($sub['plan_name'] ?? 'N/A') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $sub['status'] === 'active' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($sub['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= $sub['renewal_date'] ?></td>
                                        <td>₦<?= number_format($sub['plan_price'] ?? 0, 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No active subscriptions yet.</p>
                        <a href="<?= route('dashboard/products') ?>" class="btn btn-primary btn-sm">Browse Products</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
?>
