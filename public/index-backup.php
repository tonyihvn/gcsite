<?php
/**
 * Application Bootstrap and Entry Point
 * GINTEC Solutions
 */

// Start session
session_start();

// Define application root
define('APP_ROOT', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Error reporting
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Load environment variables
require_once APP_ROOT . '/core/DotEnv.php';
$env = new \Core\DotEnv(APP_ROOT . '/.env');
$env->load();

// Load configuration
$config = require APP_ROOT . '/config/app.php';
$dbConfig = require APP_ROOT . '/config/database.php';

// Initialize security
\Core\Security::init($config);

// Load core classes
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Model.php';
require_once APP_ROOT . '/core/Router.php';
require_once APP_ROOT . '/core/Security.php';

// Load helpers
require_once APP_ROOT . '/app/helpers/functions.php';

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
    }
});

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
$router->get('blog', 'home', 'blog');
$router->get('blog/{slug}', 'home', 'blogDetail');
$router->get('page/{slug}', 'home', 'page');
$router->get('faqs', 'home', 'faqs');

// Authentication routes
$router->get('auth/login', 'auth', 'loginForm');
$router->post('auth/login', 'auth', 'login');
$router->get('auth/register', 'auth', 'registerForm');
$router->post('auth/register', 'auth', 'register');
$router->get('auth/logout', 'auth', 'logout');
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
$router->get('admin/users', 'admin', 'users');
$router->get('admin/users/{id}', 'admin', 'userDetail');
$router->get('admin/products', 'admin', 'products');
$router->post('admin/products', 'admin', 'createProduct');
$router->post('admin/products/{id}', 'admin', 'updateProduct');
$router->post('admin/products/{id}/delete', 'admin', 'deleteProduct');
$router->get('admin/services', 'admin', 'services');
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
$router->post('admin/faqs', 'admin', 'createFaq');
$router->post('admin/faqs/{id}', 'admin', 'updateFaq');
$router->post('admin/faqs/{id}/delete', 'admin', 'deleteFaq');
$router->get('admin/blog', 'admin', 'blog');
$router->post('admin/blog', 'admin', 'createBlogPost');
$router->post('admin/blog/{id}', 'admin', 'updateBlogPost');
$router->post('admin/blog/{id}/delete', 'admin', 'deleteBlogPost');
$router->get('admin/invoices', 'admin', 'invoices');
$router->get('admin/subscriptions', 'admin', 'subscriptions');
$router->get('admin/payments', 'admin', 'payments');

// API routes for AI chat
$router->post('api/chat', 'api', 'chat');
$router->get('api/faqs/search', 'api', 'searchFaqs');

// Dispatch request
$router->dispatch();
