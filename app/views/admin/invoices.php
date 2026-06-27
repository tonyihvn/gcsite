
<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Invoice #</th>
                <th>User</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $invoice): ?>
            <tr>
                <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>
                <td><?= htmlspecialchars($invoice['user_id']) ?></td>
                <td>₦<?= number_format($invoice['amount'], 2) ?></td>
                <td><span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : 'warning' ?>"><?= ucfirst($invoice['status']) ?></span></td>
                <td><?= $invoice['due_date'] ?></td>
                <td><?= substr($invoice['created_at'], 0, 10) ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary">View</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

