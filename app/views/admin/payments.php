<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Payment Records</h3>
    <div>
        <a href="<?= route('admin/payments/export') ?>" class="btn btn-success">
            <i class="fas fa-download"></i> Export
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Reference</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Method</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($payments)): ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= $payment['id'] ?? '' ?></td>
                            <td><?= htmlspecialchars($payment['reference'] ?? '') ?></td>
                            <td><?= htmlspecialchars($payment['user_name'] ?? 'N/A') ?></td>
                            <td>₦<?= number_format($payment['amount'] ?? 0, 2) ?></td>
                            <td>
                                <span class="badge bg-<?= ($payment['status'] ?? '') === 'completed' ? 'success' : (($payment['status'] ?? '') === 'pending' ? 'warning' : 'danger') ?>">
                                    <?= ucfirst($payment['status'] ?? 'unknown') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($payment['payment_method'] ?? 'N/A') ?></td>
                            <td><?= substr($payment['created_at'] ?? '', 0, 10) ?></td>
                            <td>
                                <a href="<?= route('admin/payments/view/' . $payment['id']) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No payment records found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
