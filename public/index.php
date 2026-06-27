<?php
/**
 * Application Bootstrap and Entry Point
 * GINTEC Solutions
 * 
 * Supports both local development and shared hosting environments:
 * - Local: public/ folder is inside the project root
 * - Shared Hosting: public_html/ and gcsite/ are siblings in home directory
 */

// Start session
session_start();

// Detect environment and set application root
$currentDir = __DIR__;
$parentDir = dirname($currentDir);

// Determine APP_ROOT based on folder structure
// Try multiple paths to find the application root

$possibleRoots = [
    $parentDir . '/gcsite',                    // public_html sibling
    dirname($parentDir) . '/gcsite',           // parent directory sibling
    $parentDir,                                 // parent directory (local dev)
];

$appRoot = null;
foreach ($possibleRoots as $testPath) {
    if (is_dir($testPath) && file_exists($testPath . '/app') && file_exists($testPath . '/core')) {
        $appRoot = $testPath;
        $isSharedHosting = ($testPath === $parentDir . '/gcsite' || $testPath === dirname($parentDir) . '/gcsite');
        break;
    }
}

// If no valid root found, default to local development
if ($appRoot === null) {
    $appRoot = $parentDir;
    $isSharedHosting = false;
    
    // Log the attempt for debugging
    error_log("GINTEC Bootstrap: Could not find app/core folders. Checked paths: " . implode(", ", $possibleRoots) . ". Defaulting to: $appRoot");
}

define('APP_ROOT', $appRoot);
define('IS_SHARED_HOSTING', $isSharedHosting);

define('PUBLIC_PATH', $currentDir);

// Error reporting
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Verify APP_ROOT contains required files
if (!file_exists(APP_ROOT . '/core/DotEnv.php')) {
    die('GINTEC Setup Error: core/DotEnv.php not found at ' . APP_ROOT . '/core/DotEnv.php. ' .
        'Ensure all project files are uploaded to the correct location. ' .
        '(Current detection: ' . (IS_SHARED_HOSTING ? 'SHARED HOSTING' : 'LOCAL DEV') . ')');
}

// Load environment variables
require_once APP_ROOT . '/core/DotEnv.php';

$env = new \Core\DotEnv(APP_ROOT . '/.env');
$env->load();

// Load configuration
$config = require APP_ROOT . '/config/app.php';

$dbConfig = require APP_ROOT . '/config/database.php';

// Load core classes
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Model.php';
require_once APP_ROOT . '/core/Router.php';
require_once APP_ROOT . '/core/Security.php';
require_once APP_ROOT . '/core/Mailer.php';
require_once APP_ROOT . '/core/FileUploader.php';

// Initialize security
\Core\Security::init($config);

// Load helpers
require_once APP_ROOT . '/app/helpers/functions.php';

// Autoloader for Core namespace
spl_autoload_register(function ($class) {
    $prefix = 'Core\\';
    $base_dir = APP_ROOT . '/core/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        error_log("Core Autoloader: File not found - $file");
    }
}, true, true);

// Simple PSR-4 autoloader for App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_ROOT . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        error_log("Autoloader: File not found - $file (APP_ROOT: " . APP_ROOT . ")");
    }
}, true, true);

// Set error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return;
    }
    
    error_log("[$errno] $errstr in $errfile:$errline");
    
    if ($_ENV['APP_DEBUG'] === 'true') {
        echo "<pre>";
        echo "Error [$errno]: $errstr\n";
        echo "File: $errfile\n";
        echo "Line: $errline\n";
        echo "</pre>";
    } else {
        http_response_code(500);
        echo "An error occurred. Please try again later.";
    }
    
    return true;
});

// Set exception handler
set_exception_handler(function($exception) {
    error_log($exception->getMessage());
    
    if ($_ENV['APP_DEBUG'] === 'true') {
        echo "<pre>";
        echo "Exception: " . $exception->getMessage() . "\n";
        echo "File: " . $exception->getFile() . "\n";
        echo "Line: " . $exception->getLine() . "\n";
        echo $exception->getTraceAsString();
        echo "</pre>";
    } else {
        http_response_code(500);
        echo "An error occurred. Please try again later.";
    }
});

// Create router and register routes
$router = new \Core\Router();

// Public routes
$router->get('', 'home', 'index');
$router->get('about', 'home', 'about');
$router->get('services', 'home', 'services');
$router->get('services/{slug}', 'home', 'serviceDetail');
$router->get('products', 'home', 'products');
$router->get('products/{slug}', 'home', 'productDetail');
$router->get('contact', 'home', 'contact');
$router->post('contact', 'home', 'submitContact');
$router->get('about', 'about', 'index');
$router->get('team', 'about', 'team');
$router->get('partners', 'about', 'partners');
$router->get('blog', 'home', 'blog');
$router->get('blog/{slug}', 'home', 'blogDetail');
$router->get('page/{slug}', 'home', 'page');
$router->get('faqs', 'home', 'faqs');

// Subscription routes
$router->get('subscribe/{product_id}', 'home', 'subscribeForm');
$router->post('subscribe/{product_id}', 'home', 'createSubscription');

// Authentication routes
$router->get('auth/login', 'auth', 'loginForm');
$router->post('auth/login', 'auth', 'login');
$router->get('auth/register', 'auth', 'registerForm');
$router->post('auth/register', 'auth', 'register');
$router->get('auth/logout', 'auth', 'logout');
$router->get('auth/forgot-password', 'auth', 'forgotPassword');
$router->post('auth/forgot-password', 'auth', 'forgotPassword');
$router->get('auth/reset-password/{token}', 'auth', 'resetPasswordForm');
$router->post('auth/reset-password', 'auth', 'resetPassword');

// User Dashboard routes
$router->get('dashboard', 'user', 'dashboard');
$router->get('dashboard/profile', 'user', 'profile');
$router->post('dashboard/profile', 'user', 'updateProfile');
$router->get('dashboard/subscriptions', 'user', 'subscriptions');
$router->get('dashboard/invoices', 'user', 'invoices');
$router->get('dashboard/invoices/{id}', 'user', 'invoiceDetail');
$router->get('dashboard/payments', 'user', 'payments');
$router->get('dashboard/products', 'user', 'products');

// Admin routes
$router->get('admin', 'admin', 'dashboard');
$router->get('admin/settings', 'admin', 'settings');
$router->post('admin/settings', 'admin', 'updateSettings');
$router->get('admin/theme', 'admin', 'themeSettings');
$router->post('admin/theme', 'admin', 'updateThemeSettings');
$router->get('theme.css', 'admin', 'themeCSS');
$router->get('admin/menus', 'admin', 'menus');
$router->post('admin/menu/save', 'admin', 'saveMenu');
$router->get('admin/menu/delete/{id}', 'admin', 'deleteMenu');
$router->post('admin/menus/reorder', 'admin', 'reorderMenus');
$router->post('admin/menu/update-order', 'admin', 'updateMenuOrder');
$router->post('admin/menu/create-from-page/{id}', 'admin', 'createMenuFromPage');
$router->get('admin/users', 'admin', 'users');
$router->get('admin/users/{id}', 'admin', 'userDetail');
$router->get('admin/products', 'admin', 'products');
$router->get('admin/products/create', 'admin', 'createProductForm');
$router->get('admin/products/{id}', 'admin', 'editProductForm');
$router->get('admin/products/{id}/edit', 'admin', 'editProductForm');
$router->post('admin/products', 'admin', 'createProduct');
$router->post('admin/products/{id}', 'admin', 'updateProduct');
$router->post('admin/products/{id}/delete', 'admin', 'deleteProduct');
$router->get('admin/services', 'admin', 'services');
$router->get('admin/services/create', 'admin', 'createServiceForm');
$router->get('admin/services/{id}', 'admin', 'editServiceForm');
$router->get('admin/services/{id}/edit', 'admin', 'editServiceForm');
$router->post('admin/services', 'admin', 'createService');
$router->post('admin/services/{id}', 'admin', 'updateService');
$router->post('admin/services/{id}/delete', 'admin', 'deleteService');
$router->get('admin/pages', 'admin', 'pages');
$router->get('admin/pages/create', 'admin', 'createPageForm');
$router->post('admin/pages', 'admin', 'createPage');
$router->get('admin/pages/{id}/edit', 'admin', 'editPageForm');
$router->post('admin/pages/{id}', 'admin', 'updatePage');
$router->post('admin/pages/{id}/delete', 'admin', 'deletePage');
$router->get('admin/slides', 'admin', 'slides');
$router->get('admin/slides/create', 'admin', 'createSlideForm');
$router->get('admin/slides/edit/{id}', 'admin', 'editSlideForm');
$router->get('admin/slides/{id}/edit', 'admin', 'editSlideForm');
$router->get('admin/slides/delete/{id}', 'admin', 'deleteSlide');
$router->post('admin/slides', 'admin', 'createSlide');
$router->post('admin/slides/{id}', 'admin', 'updateSlide');
$router->post('admin/slides/{id}/delete', 'admin', 'deleteSlide');
$router->get('admin/feedbacks', 'admin', 'feedbacks');
$router->get('admin/feedbacks/{id}', 'admin', 'feedbackDetail');
$router->post('admin/feedbacks/{id}', 'admin', 'respondFeedback');
$router->get('admin/media', 'admin', 'media');
$router->post('admin/media/upload', 'admin', 'uploadMedia');
$router->post('admin/media/{id}/delete', 'admin', 'deleteMedia');
$router->get('admin/faqs', 'admin', 'faqs');
$router->get('admin/faqs/create', 'admin', 'createFaqForm');
$router->get('admin/faqs/{id}/edit', 'admin', 'editFaqForm');
$router->post('admin/faqs', 'admin', 'createFaq');
$router->post('admin/faqs/{id}', 'admin', 'updateFaq');
$router->post('admin/faqs/{id}/delete', 'admin', 'deleteFaq');
$router->get('admin/blog', 'admin', 'blog');
$router->get('admin/blog/create', 'admin', 'createBlogPostForm');
$router->get('admin/blog/edit/{id}', 'admin', 'editBlogPostForm');
$router->get('admin/blog/{id}/edit', 'admin', 'editBlogPostForm');
$router->get('admin/blog/delete/{id}', 'admin', 'deleteBlogPost');
$router->post('admin/blog', 'admin', 'createBlogPost');
$router->post('admin/blog/{id}', 'admin', 'updateBlogPost');
$router->post('admin/blog/{id}/delete', 'admin', 'deleteBlogPost');
$router->get('admin/team', 'admin', 'team');
$router->get('admin/team/create', 'admin', 'createTeamMemberForm');
$router->get('admin/team/edit/{id}', 'admin', 'editTeamMemberForm');
$router->get('admin/team/delete/{id}', 'admin', 'deleteTeamMember');
$router->post('admin/team', 'admin', 'createTeamMember');
$router->post('admin/team/{id}', 'admin', 'updateTeamMember');
$router->post('admin/team/{id}/delete', 'admin', 'deleteTeamMember');
$router->get('admin/partners', 'admin', 'partners');
$router->get('admin/partners/create', 'admin', 'createPartnerForm');
$router->get('admin/partners/edit/{id}', 'admin', 'editPartnerForm');
$router->get('admin/partners/delete/{id}', 'admin', 'deletePartner');
$router->post('admin/partners', 'admin', 'createPartner');
$router->post('admin/partners/{id}', 'admin', 'updatePartner');
$router->post('admin/partners/{id}/delete', 'admin', 'deletePartner');
$router->get('admin/about', 'admin', 'about');
$router->get('admin/about/create', 'admin', 'createAboutSectionForm');
$router->get('admin/about/edit/{id}', 'admin', 'editAboutSectionForm');
$router->get('admin/about/delete/{id}', 'admin', 'deleteAboutSection');
$router->post('admin/about', 'admin', 'createAboutSection');
$router->post('admin/about/{id}', 'admin', 'updateAboutSection');
$router->post('admin/about/{id}/delete', 'admin', 'deleteAboutSection');
$router->get('admin/invoices', 'admin', 'invoices');
$router->get('admin/subscriptions', 'admin', 'subscriptions');
$router->get('admin/payments', 'admin', 'payments');

// API routes for AI chat
$router->post('api/chat', 'api', 'chat');
$router->get('api/faqs/search', 'api', 'searchFaqs');

// Dispatch request
$router->dispatch();
