
<div class="container py-5">
    <h1 class="mb-4">My Invoices</h1>

    <?php if (!empty($invoices)): ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Invoice #</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($invoice['invoice_number']) ?></strong></td>
                    <td>₦<?= number_format($invoice['amount'], 2) ?></td>
                    <td>
                        <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : ($invoice['status'] === 'overdue' ? 'danger' : 'warning') ?>">
                            <?= ucfirst($invoice['status']) ?>
                        </span>
                    </td>
                    <td><?= $invoice['due_date'] ?></td>
                    <td><?= $invoice['created_at'] ?></td>
                    <td>
                        <a href="<?= route('dashboard/invoices/' . $invoice['id']) ?>" class="btn btn-sm btn-outline-primary">
                            View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        No invoices yet.
    </div>
    <?php endif; ?>
</div>
