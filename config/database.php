<?php
/**
 * Database Configuration
 * GINTEC Solutions Consults Ltd
 */

require_once __DIR__ . '/../core/DotEnv.php';

$env = new \Core\DotEnv(__DIR__ . '/../.env');
$env->load();

return [
    'default' => $_ENV['DB_CONNECTION'] ?? 'mysql',
    
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => $_ENV['DB_HOST'] ?? 'localhost',
            'port'      => $_ENV['DB_PORT'] ?? 3306,
            'database'  => $_ENV['DB_NAME'] ?? 'gintec_solutions',
            'username'  => $_ENV['DB_USER'] ?? 'root',
            'password'  => $_ENV['DB_PASS'] ?? '',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => 'gintec_',
        ],
    ],
];
