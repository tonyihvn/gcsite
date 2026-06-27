<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Feedback Details</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <h6>Name</h6>
                <p><?= htmlspecialchars($feedback['name']) ?></p>
            </div>
            <div class="col-md-6">
                <h6>Email</h6>
                <p><?= htmlspecialchars($feedback['email']) ?></p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <h6>Phone</h6>
                <p><?= htmlspecialchars($feedback['phone'] ?? 'N/A') ?></p>
            </div>
            <div class="col-md-6">
                <h6>Status</h6>
                <p>
                    <span class="badge bg-<?= $feedback['status'] === 'new' ? 'danger' : ($feedback['status'] === 'reviewed' ? 'warning' : 'success') ?>">
                        <?= ucfirst($feedback['status']) ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="mb-3">
            <h6>Subject</h6>
            <p><?= htmlspecialchars($feedback['subject'] ?? 'N/A') ?></p>
        </div>

        <div class="mb-3">
            <h6>Message</h6>
            <div class="bg-light p-3 rounded">
                <?= nl2br(htmlspecialchars($feedback['message'])) ?>
            </div>
        </div>

        <div class="mb-3">
            <h6>Admin Notes</h6>
            <div class="bg-light p-3 rounded">
                <?= $feedback['admin_notes'] ? nl2br(htmlspecialchars($feedback['admin_notes'])) : '<em>No notes yet</em>' ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <h6>Created</h6>
                <p><?= date('M d, Y H:i', strtotime($feedback['created_at'])) ?></p>
            </div>
            <div class="col-md-6">
                <h6>Updated</h6>
                <p><?= date('M d, Y H:i', strtotime($feedback['updated_at'])) ?></p>
            </div>
        </div>

        <hr>

        <form method="POST" action="<?= route('admin/feedbacks/' . $feedback['id']) ?>" class="row g-3">
            <?= csrf_field() ?>

            <div class="col-12">
                <label class="form-label">Response Notes</label>
                <textarea class="form-control" name="admin_notes" rows="4" placeholder="Add your notes..."><?= htmlspecialchars($feedback['admin_notes'] ?? '') ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="new" <?= $feedback['status'] === 'new' ? 'selected' : '' ?>>New</option>
                    <option value="reviewed" <?= $feedback['status'] === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                    <option value="resolved" <?= $feedback['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Update Feedback</button>
                <a href="<?= route('admin/feedbacks') ?>" class="btn btn-secondary">Back to Feedbacks</a>
            </div>
        </form>
    </div>
</div>
