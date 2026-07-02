<?php
$badges = ['pending' => 'secondary', 'contacted' => 'info', 'converted' => 'success', 'paid' => 'primary'];
?>
<div class="container-fluid">
    <h2 class="mb-4">Referral Management</h2>

    <?php if ($msg = get_flash('success')): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = get_flash('error')): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Summary -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100"><div class="card-body">
                <h3 class="text-primary mb-0"><?= (int)$total_clicks ?></h3>
                <small class="text-muted">Total Link Clicks</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100"><div class="card-body">
                <h3 class="text-success mb-0"><?= (int)$total_leads ?></h3>
                <small class="text-muted">Total Referred Contacts</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100"><div class="card-body">
                <h3 class="text-info mb-0"><?= count($referrers) ?></h3>
                <small class="text-muted">Active Referrers</small>
            </div></div>
        </div>
    </div>

    <!-- Leaderboard -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Referrer Leaderboard</h5></div>
        <div class="card-body">
            <?php if (!empty($referrers)): ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Referrer</th><th>Email</th><th>Code</th><th>Clicks</th><th>Contacts</th><th>Converted</th></tr></thead>
                        <tbody>
                            <?php foreach ($referrers as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['name']) ?></td>
                                    <td><?= htmlspecialchars($r['email']) ?></td>
                                    <td><code><?= htmlspecialchars($r['referral_code']) ?></code></td>
                                    <td><span class="badge bg-primary"><?= (int)$r['clicks'] ?></span></td>
                                    <td><?= (int)$r['leads'] ?></td>
                                    <td><span class="badge bg-success"><?= (int)$r['converted'] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">No referral activity yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- All referred contacts -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Referred Contacts</h5></div>
        <div class="card-body">
            <?php if (!empty($leads)): ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Contact</th><th>Phone</th><th>Interested In</th>
                                <th>Referred By</th><th>Status</th><th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leads as $lead): ?>
                                <?php $st = $lead['status'] ?? 'pending'; ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($lead['name']) ?>
                                        <?php if (!empty($lead['email'])): ?><br><small class="text-muted"><?= htmlspecialchars($lead['email']) ?></small><?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($lead['phone']) ?></td>
                                    <td><?= htmlspecialchars($lead['interested_in'] ?? '') ?></td>
                                    <td>
                                        <?= htmlspecialchars(trim($lead['first_name'] . ' ' . $lead['last_name'])) ?><br>
                                        <small class="text-muted"><code><?= htmlspecialchars($lead['referral_code'] ?? '') ?></code></small>
                                    </td>
                                    <td><span class="badge bg-<?= $badges[$st] ?? 'secondary' ?>"><?= ucfirst($st) ?></span></td>
                                    <td>
                                        <form method="POST" action="<?= route('admin/referrals/' . $lead['id'] . '/status') ?>" class="d-flex gap-1">
                                            <?= csrf_field() ?>
                                            <select name="status" class="form-select form-select-sm" style="width:auto;">
                                                <?php foreach (['pending','contacted','converted','paid'] as $opt): ?>
                                                    <option value="<?= $opt ?>" <?= $st === $opt ? 'selected' : '' ?>><?= ucfirst($opt) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button class="btn btn-sm btn-outline-primary" type="submit">Save</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php if (!empty($lead['notes'])): ?>
                                    <tr><td colspan="6" class="text-muted small">Notes: <?= htmlspecialchars($lead['notes']) ?></td></tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">No referred contacts have been submitted yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
