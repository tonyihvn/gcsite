<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Dashboard' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= route('theme.css') ?>">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="position-sticky pt-3">
                    <a class="navbar-brand ps-3 mb-4 text-white" href="<?= route('admin') ?>">
                        <i class="fas fa-cog"></i> GINTEC Admin
                    </a>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin') ?>">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/settings') ?>">
                                <i class="fas fa-sliders-h"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/theme') ?>">
                                <i class="fas fa-palette"></i> Theme & Colors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/menus') ?>">
                                <i class="fas fa-bars"></i> Menu Manager
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/users') ?>">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/products') ?>">
                                <i class="fas fa-box"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/services') ?>">
                                <i class="fas fa-concierge-bell"></i> Services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/pages') ?>">
                                <i class="fas fa-file"></i> Pages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/slides') ?>">
                                <i class="fas fa-images"></i> Slides
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/faqs') ?>">
                                <i class="fas fa-question-circle"></i> FAQs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/blog') ?>">
                                <i class="fas fa-blog"></i> Blog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/team') ?>">
                                <i class="fas fa-people-group"></i> Team
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/partners') ?>">
                                <i class="fas fa-handshake"></i> Partners
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/about') ?>">
                                <i class="fas fa-info-circle"></i> About
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/feedbacks') ?>">
                                <i class="fas fa-comments"></i> Feedbacks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/media') ?>">
                                <i class="fas fa-image"></i> Media
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/subscriptions') ?>">
                                <i class="fas fa-credit-card"></i> Subscriptions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/invoices') ?>">
                                <i class="fas fa-receipt"></i> Invoices
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('admin/payments') ?>">
                                <i class="fas fa-money-bill"></i> Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('') ?>">
                                <i class="fas fa-globe"></i> View Site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= route('auth/logout') ?>">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?= $page_title ?? 'Admin Dashboard' ?></h1>
                    <div>
                        <a href="<?= route('dashboard') ?>" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if ($flash = flash()): ?>
                    <?php foreach (['success', 'error', 'info', 'warning'] as $type): ?>
                        <?php if (isset($flash[$type])): ?>
                            <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                                <?= $flash[$type] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Page Content -->
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script src="<?= asset('js/admin.js') ?>"></script>
    <script>
        // Initialize CKEditor for ALL textarea elements automatically
        document.querySelectorAll('textarea').forEach(function(textarea) {
            ClassicEditor
                .create(textarea, {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    }
                })
                .catch(error => console.error(error));
        });
    </script>
</body>
</html>
