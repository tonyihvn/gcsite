
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= route('dashboard/profile') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-user"></i> Personal Info
                </a>
                <a href="<?= route('dashboard/profile') ?>" class="list-group-item list-group-item-action active">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
                <a href="<?= route('dashboard') ?>" class="list-group-item list-group-item-action">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Your Profile</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= route('dashboard/profile') ?>">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Account Status</label>
                            <div>
                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="<?= route('dashboard') ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
?>
