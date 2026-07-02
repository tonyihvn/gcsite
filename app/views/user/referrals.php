<?php
$refBase = rtrim($base_url, '/');
?>
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Dashboard</h5>
                    <p class="text-muted mb-0">Welcome, <?= htmlspecialchars(auth()['first_name'] ?? 'User') ?>!</p>
                </div>
            </div>
            <div class="list-group">
                <a href="<?= route('dashboard') ?>" class="list-group-item list-group-item-action">
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
                <a href="<?= route('dashboard/referrals') ?>" class="list-group-item list-group-item-action active">
                    <i class="fas fa-share-nodes"></i> Referral Program
                </a>
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-9">
            <h2 class="mb-4">Referral Program</h2>

            <?php if ($msg = get_flash('success')): ?>
                <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            <?php if ($msg = get_flash('error')): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            <?php if ($errs = get_flash('errors')): ?>
                <div class="alert alert-danger"><ul class="mb-0">
                    <?php foreach ((array)$errs as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul></div>
            <?php endif; ?>

            <!-- Referral code -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Your Referral Code</h5>
                    <p class="text-muted">Share your unique links below. Every visit is tracked, and when a referred client engages us you earn a reward.</p>
                    <div class="input-group input-group-lg mb-2" style="max-width: 360px;">
                        <input type="text" class="form-control fw-bold text-center" id="refCode" value="<?= htmlspecialchars($referral_code) ?>" readonly>
                        <button class="btn btn-outline-primary" type="button" onclick="copyText('refCode', this)">Copy</button>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-3 col-6 mb-3">
                    <div class="card text-center h-100"><div class="card-body">
                        <h3 class="text-primary mb-0"><?= (int)$total_clicks ?></h3>
                        <small class="text-muted">Total Clicks</small>
                    </div></div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card text-center h-100"><div class="card-body">
                        <h3 class="text-info mb-0"><?= (int)$service_clicks ?></h3>
                        <small class="text-muted">Service Clicks</small>
                    </div></div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card text-center h-100"><div class="card-body">
                        <h3 class="text-warning mb-0"><?= (int)$product_clicks ?></h3>
                        <small class="text-muted">Product Clicks</small>
                    </div></div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card text-center h-100"><div class="card-body">
                        <h3 class="text-success mb-0"><?= (int)$converted_count ?></h3>
                        <small class="text-muted">Converted</small>
                    </div></div>
                </div>
            </div>

            <!-- Shareable links -->
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Your Shareable Links</h5></div>
                <div class="card-body">
                    <?php if (!empty($services)): ?>
                        <h6 class="text-muted">Services</h6>
                        <?php foreach ($services as $s): ?>
                            <?php $url = $refBase . '/ref/' . $referral_code . '/services/' . $s['slug']; $fid = 'svc_' . $s['id']; ?>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text" style="min-width: 160px;"><?= htmlspecialchars($s['name']) ?></span>
                                <input type="text" class="form-control" id="<?= $fid ?>" value="<?= htmlspecialchars($url) ?>" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyText('<?= $fid ?>', this)">Copy</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($products)): ?>
                        <h6 class="text-muted mt-3">Products</h6>
                        <?php foreach ($products as $p): ?>
                            <?php $url = $refBase . '/ref/' . $referral_code . '/products/' . $p['slug']; $fid = 'prod_' . $p['id']; ?>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text" style="min-width: 160px;"><?= htmlspecialchars($p['name']) ?></span>
                                <input type="text" class="form-control" id="<?= $fid ?>" value="<?= htmlspecialchars($url) ?>" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyText('<?= $fid ?>', this)">Copy</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (empty($services) && empty($products)): ?>
                        <p class="text-muted mb-0">No services or products are available to refer yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Add referred contact -->
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Add a Referred Contact</h5></div>
                <div class="card-body">
                    <p class="text-muted">Record the people you referred so we can follow up and credit you when it converts to business.</p>
                    <form method="POST" action="<?= route('dashboard/referrals/leads') ?>" class="row g-3">
                        <?= csrf_field() ?>
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number *</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email (optional)</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Interested In (optional)</label>
                            <input type="text" name="interested_in" class="form-control" placeholder="e.g. School Management System">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Contact</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Referred contacts list -->
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Your Referred Contacts</h5></div>
                <div class="card-body">
                    <?php if (!empty($leads)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th><th>Phone</th><th>Interested In</th><th>Status</th><th>Date</th><th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($leads as $lead): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($lead['name']) ?></td>
                                            <td><?= htmlspecialchars($lead['phone']) ?></td>
                                            <td><?= htmlspecialchars($lead['interested_in'] ?? '') ?></td>
                                            <td>
                                                <?php
                                                    $badges = ['pending' => 'secondary', 'contacted' => 'info', 'converted' => 'success', 'paid' => 'primary'];
                                                    $st = $lead['status'] ?? 'pending';
                                                ?>
                                                <span class="badge bg-<?= $badges[$st] ?? 'secondary' ?>"><?= ucfirst($st) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars(date('M j, Y', strtotime($lead['created_at']))) ?></td>
                                            <td>
                                                <form method="POST" action="<?= route('dashboard/referrals/leads/' . $lead['id'] . '/delete') ?>" onsubmit="return confirm('Remove this contact?');">
                                                    <?= csrf_field() ?>
                                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">You haven't added any referred contacts yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent clicks -->
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Recent Link Activity</h5></div>
                <div class="card-body">
                    <?php if (!empty($recent_clicks)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Type</th><th>Item</th><th>When</th></tr></thead>
                                <tbody>
                                    <?php foreach ($recent_clicks as $c): ?>
                                        <tr>
                                            <td><span class="badge bg-<?= $c['link_type'] === 'service' ? 'info' : 'warning' ?>"><?= ucfirst($c['link_type']) ?></span></td>
                                            <td><?= htmlspecialchars($c['item_slug'] ?? '') ?></td>
                                            <td><?= htmlspecialchars(date('M j, Y g:i A', strtotime($c['created_at']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No link activity yet. Start sharing your links above!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyText(id, btn) {
    var input = document.getElementById(id);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function () {
        var original = btn.innerHTML;
        btn.innerHTML = 'Copied!';
        setTimeout(function () { btn.innerHTML = original; }, 1500);
    });
}
</script>
