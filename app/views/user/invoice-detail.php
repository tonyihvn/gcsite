
<div class="container py-5">
    <h1 class="mb-4">Invoice: <?= htmlspecialchars($invoice['invoice_number']) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Invoice Number:</strong> <?= htmlspecialchars($invoice['invoice_number']) ?></p>
                            <p><strong>Created:</strong> <?= $invoice['created_at'] ?></p>
                            <p><strong>Due Date:</strong> <?= $invoice['due_date'] ?></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p><strong>Status:</strong></p>
                            <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : 'warning' ?> fs-6">
                                <?= ucfirst($invoice['status']) ?>
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p><strong>Amount Due:</strong> <strong class="text-primary fs-5">₦<?= number_format($invoice['amount'], 2) ?></strong></p>
                        <p><strong>Currency:</strong> <?= $invoice['currency'] ?></p>
                        <?php if (!empty($invoice['notes'])): ?>
                        <p><strong>Notes:</strong> <?= nl2br(htmlspecialchars($invoice['notes'])) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                        <?php if ($invoice['status'] !== 'paid'): ?>
                        <button class="btn btn-success">
                            <i class="fas fa-money-bill"></i> Pay Now
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6>Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="<?= route('dashboard/invoices') ?>" class="btn btn-outline-secondary btn-sm">
                            Back to Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
