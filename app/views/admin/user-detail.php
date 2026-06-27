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
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                <i class="fas fa-key"></i> Change Password
            </button>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change User Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= route('admin/users/' . $user['id'] . '/change-password') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        <strong>User:</strong> <?= htmlspecialchars($user['email']) ?>
                    </p>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        <small class="text-muted d-block mt-1">Minimum 8 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8">
                        <small class="text-muted d-block mt-1">Must match new password</small>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> The user will need to use the new password to log in.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
