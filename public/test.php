<?php
// Test if all required files can be loaded
$APP_ROOT = dirname(dirname(__FILE__));

echo "APP_ROOT: $APP_ROOT\n";
echo "Files to check:\n";

$files = [
    'core/DotEnv.php',
    'core/Database.php',
    'core/Controller.php',
    'core/Model.php',
    'core/Router.php',
    'core/Security.php',
    'config/app.php',
    'config/database.php',
    'app/helpers/functions.php',
    '.env',
];

foreach ($files as $file) {
    $path = $APP_ROOT . '/' . $file;
    $exists = file_exists($path) ? 'EXISTS' : 'MISSING';
    echo "  ✓ $file: $exists\n";
}
