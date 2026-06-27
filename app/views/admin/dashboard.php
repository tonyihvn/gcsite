
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Admin Dashboard</h1>
        </div>
        <div class="col-md-6 text-end">
            <p class="text-muted">Welcome, <?= auth()['first_name'] ?? 'User' ?> (CEO Panel)</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary"><?= $total_users ?></h3>
                    <p class="text-muted">Total Users</p>
                    <a href="<?= route('admin/users') ?>" class="btn btn-sm btn-outline-primary">Manage</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info"><?= $total_products ?></h3>
                    <p class="text-muted">Products</p>
                    <a href="<?= route('admin/products') ?>" class="btn btn-sm btn-outline-info">Manage</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success"><?= $active_subscriptions ?></h3>
                    <p class="text-muted">Active Subscriptions</p>
                    <a href="<?= route('admin/subscriptions') ?>" class="btn btn-sm btn-outline-success">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning"><?= $pending_feedbacks ?></h3>
                    <p class="text-muted">New Feedbacks</p>
                    <a href="<?= route('admin/feedbacks') ?>" class="btn btn-sm btn-outline-warning">Review</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= route('admin/settings') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-sliders-h"></i> Site Settings
                    </a>
                    <a href="<?= route('admin/products') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                    <a href="<?= route('admin/services') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus"></i> Add New Service
                    </a>
                    <a href="<?= route('admin/pages/create') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus"></i> Create New Page
                    </a>
                    <a href="<?= route('admin/slides') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-images"></i> Manage Slides
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Site Information</h5>
                </div>
                <div class="card-body">
                    <?php use App\Models\Setting; ?>
                    <p><strong>Company:</strong> <?= Setting::get('company_name', config('company.name')) ?></p>
                    <p><strong>Address:</strong> <?= Setting::get('company_address', config('company.address')) ?></p>
                    <p><strong>Phone:</strong> <a href="tel:<?= Setting::get('company_phone', config('company.phone')) ?>"><?= Setting::get('company_phone', config('company.phone')) ?></a></p>
                    <p><strong>Email:</strong> <a href="mailto:<?= Setting::get('company_email', config('company.email')) ?>"><?= Setting::get('company_email', config('company.email')) ?></a></p>
                    <p><strong>Website:</strong> <?= Setting::get('company_website', config('company.website')) ?></p>
                    <p><strong>CEO:</strong> <?= Setting::get('company_ceo', config('company.ceo')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

