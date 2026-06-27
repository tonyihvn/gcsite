<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Step 1: Session start\n";
session_start();

echo "Step 2: Define constants\n";
define('APP_ROOT', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

echo "Step 3: Load DotEnv\n";
require_once APP_ROOT . '/core/DotEnv.php';

echo "Step 4: Create and load environment\n";
$env = new \Core\DotEnv(APP_ROOT . '/.env');
$env->load();

echo "Step 5: Load configuration\n";
$config = require APP_ROOT . '/config/app.php';
$dbConfig = require APP_ROOT . '/config/database.php';

echo "Step 6: Initialize Security\n";
\Core\Security::init($config);

echo "Step 7: Load core classes\n";
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Model.php';
require_once APP_ROOT . '/core/Router.php';
require_once APP_ROOT . '/core/Security.php';

echo "Step 8: Load helpers\n";
require_once APP_ROOT . '/app/helpers/functions.php';

echo "Step 9: Setup autoloader\n";
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

$GLOBALS['APP_ROOT'] = APP_ROOT;

echo "Step 10: Get request info\n";
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestPath = str_replace(substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')), '', $requestPath);
$requestPath = trim($requestPath, '/');

echo "Request Path: '" . $requestPath . "'\n";
echo "Request Method: " . $requestMethod . "\n";

echo "Step 11: Done - Ready for routing\n";
