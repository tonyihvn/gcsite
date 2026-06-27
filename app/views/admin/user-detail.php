<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">User Details</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <h6>Name</h6>
                <p><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
            </div>
            <div class="col-md-6">
                <h6>Email</h6>
                <p><?= htmlspecialchars($user['email']) ?></p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <h6>Phone</h6>
                <p><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></p>
            </div>
            <div class="col-md-6">
                <h6>Role</h6>
                <p>
                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'vendor' ? 'warning' : 'info') ?>">
                        <?= ucfirst($user['role']) ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <h6>Status</h6>
                <p>
                    <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                        <?= ucfirst($user['status']) ?>
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <h6>Last Login</h6>
                <p><?= $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'Never' ?></p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <h6>Created</h6>
                <p><?= date('M d, Y H:i', strtotime($user['created_at'])) ?></p>
            </div>
            <div class="col-md-6">
                <h6>Updated</h6>
                <p><?= date('M d, Y H:i', strtotime($user['updated_at'])) ?></p>
            </div>
        </div>

        <hr>

        <div class="d-flex gap-2">
            <a href="<?= route('admin/users') ?>" class="btn btn-secondary">Back to Users</a>
        </div>
    </div>
</div>
