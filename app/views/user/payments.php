
<div class="container py-5">
    <h1 class="mb-4">Payment History</h1>

    <?php if (!empty($invoices)): ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Invoice #</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Payment Method</th>
                    <th>Paid Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>
                    <td>₦<?= number_format($invoice['amount'], 2) ?></td>
                    <td>
                        <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : 'warning' ?>">
                            <?= ucfirst($invoice['status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($invoice['payment_method'] ?? 'N/A') ?></td>
                    <td><?= $invoice['paid_date'] ?? '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        No payment history yet.
    </div>
    <?php endif; ?>
</div>
