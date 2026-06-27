<?php
$APP_ROOT = dirname(dirname(__FILE__));

// Load environment
require_once $APP_ROOT . '/core/DotEnv.php';
$env = new \Core\DotEnv($APP_ROOT . '/.env');
$env->load();

// Load config
$config = require $APP_ROOT . '/config/app.php';
$dbConfig = require $APP_ROOT . '/config/database.php';

// Load core classes
require_once $APP_ROOT . '/core/Database.php';
require_once $APP_ROOT . '/core/Controller.php';
require_once $APP_ROOT . '/core/Model.php';
require_once $APP_ROOT . '/core/Router.php';
require_once $APP_ROOT . '/core/Security.php';

// Load helpers
require_once $APP_ROOT . '/app/helpers/functions.php';

// Simple PSR-4 autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = $GLOBALS['APP_ROOT'] . '/app/';

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

$GLOBALS['APP_ROOT'] = $APP_ROOT;

try {
    // Test loading the Slide model
    $slide = new \App\Models\Slide();
    echo "✓ Slide model loaded successfully\n";
    
    // Try to get active slides
    $slides = $slide->getActive();
    echo "✓ Slides retrieved: " . count($slides) . " total\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
