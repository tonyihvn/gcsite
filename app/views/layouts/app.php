<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'GINTEC Solutions' ?></title>
    <!-- Favicon -->
    <?php 
    $site_favicon = \App\Models\Setting::get('site_favicon', '');
    if (!empty($site_favicon)): 
    ?>
        <link rel="icon" type="image/x-icon" href="<?= \Core\FileUploader::getImageUrl($site_favicon) ?>">
    <?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= route('theme.css') ?>">
</head>
<body>
    <?php use App\Models\Setting; ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= route('') ?>" style="display: flex; align-items: center; gap: 12px; min-width: 200px;">
                <?php 
                $site_logo = Setting::get('site_logo', '');
                $company_name = Setting::get('company_name', 'GINTEC Solutions');
                if (!empty($site_logo)): 
                ?>
                    <img src="<?= \Core\FileUploader::getImageUrl($site_logo) ?>" alt="Site Logo" style="height: 40px; max-width: 120px;">
                    <span style="display: none;" class="d-none d-md-inline"><?= htmlspecialchars($company_name) ?></span>
                <?php else: ?>
                    <i class="fas fa-rocket" style="font-size: 24px;"></i>
                    <span><?= htmlspecialchars($company_name) ?></span>
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php 
                    // Dynamically load menus from database
                    try {
                        $menuModel = new \App\Models\Menu();
                        $menus = $menuModel->getMenuHierarchy();
                        if (!empty($menus)) {
                            foreach ($menus as $menu) {
                                if ($menu['status'] !== 'active') continue;
                                
                                // Check if menu has children
                                $hasChildren = !empty($menu['children']);
                                
                                if ($hasChildren) {
                                    // Render as dropdown
                                    echo '<li class="nav-item dropdown">';
                                    echo '<a class="nav-link dropdown-toggle" href="' . htmlspecialchars($menu['url'] ?? '#') . '" id="menu' . $menu['id'] . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
                                    if (!empty($menu['icon'])) {
                                        echo '<i class="' . htmlspecialchars($menu['icon']) . ' me-1"></i>';
                                    }
                                    echo htmlspecialchars($menu['label']);
                                    echo '</a>';
                                    echo '<ul class="dropdown-menu" aria-labelledby="menu' . $menu['id'] . '">';
                                    
                                    // Render child menu items
                                    foreach ($menu['children'] as $child) {
                                        if ($child['status'] === 'active') {
                                            echo '<li><a class="dropdown-item" href="' . htmlspecialchars($child['url'] ?? '#') . '">';
                                            if (!empty($child['icon'])) {
                                                echo '<i class="' . htmlspecialchars($child['icon']) . ' me-2"></i>';
                                            }
                                            echo htmlspecialchars($child['label']);
                                            echo '</a></li>';
                                        }
                                    }
                                    
                                    echo '</ul></li>';
                                } else {
                                    // Render as simple link
                                    echo '<li class="nav-item">';
                                    echo '<a class="nav-link" href="' . htmlspecialchars($menu['url'] ?? '#') . '">';
                                    if (!empty($menu['icon'])) {
                                        echo '<i class="' . htmlspecialchars($menu['icon']) . ' me-1"></i>';
                                    }
                                    echo htmlspecialchars($menu['label']);
                                    echo '</a></li>';
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // If menus table doesn't exist yet, continue
                    }
                    ?>
                    <?php 
                    // Custom pages are now managed through the Menu Manager
                    // No separate page loading - all pages should have corresponding menu items created in the Menu Manager
                    ?>
                    <?php if (is_auth()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= auth()['first_name'] ?? auth()->first_name ?? 'User' ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" style="z-index: 1050;">
                                <li><a class="dropdown-item" href="<?= route('dashboard') ?>">Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?= route('dashboard/profile') ?>">Profile</a></li>
                                <?php if (is_admin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= route('admin') ?>">Admin Panel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= route('auth/logout') ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= route('auth/login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="<?= route('auth/register') ?>">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($flash = flash()): ?>
        <div class="container mt-3">
            <?php foreach (['success', 'error', 'info', 'warning'] as $type): ?>
                <?php if (isset($flash[$type])): ?>
                    <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                        <?= $flash[$type] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>GINTEC Solutions</h5>
                    <p>Leading provider of innovative IT solutions and consultancy services.</p>
                    <div>
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= route('') ?>" class="text-white-50">Home</a></li>
                        <li><a href="<?= route('services') ?>" class="text-white-50">Services</a></li>
                        <li><a href="<?= route('products') ?>" class="text-white-50">Products</a></li>
                        <li><a href="<?= route('contact') ?>" class="text-white-50">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Contact Info</h6>
                    <p class="mb-1">
                        <i class="fas fa-phone"></i> 
                        <a href="tel:<?= Setting::get('company_phone', '') ?>" class="text-white-50"><?= Setting::get('company_phone', 'Not available') ?></a>
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-envelope"></i> 
                        <a href="mailto:<?= Setting::get('company_email', '') ?>" class="text-white-50"><?= Setting::get('company_email', 'Not available') ?></a>
                    </p>
                    <p>
                        <i class="fas fa-map-marker-alt"></i> 
                        <span class="text-white-50"><?= Setting::get('company_address', 'Not available') ?></span>
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Newsletter</h6>
                    <form class="input-group">
                        <input type="email" class="form-control" placeholder="Your email" required>
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <hr class="bg-white-50">
            <div class="text-center text-white-50">
                <p>&copy; <?= date('Y') ?> GINTEC Solutions Consults Ltd. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- AI Chat Widget -->
    <div id="chat-widget" class="chat-widget">
        <div class="chat-header" onclick="toggleChat()">
            <i class="fas fa-comments"></i>
            <span>Chat with us</span>
            <button class="close-btn">&times;</button>
        </div>
        <div id="chat-body" class="chat-body" style="display: none;">
            <div id="messages" class="messages"></div>
            <div class="chat-input">
                <input type="text" id="user-input" placeholder="Ask a question...">
                <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="<?= asset('js/chat.js') ?>"></script>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
