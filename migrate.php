<?php
/**
 * Database Migration Runner
 * GINTEC Solutions
 */

// Load environment variables
require_once __DIR__ . '/core/DotEnv.php';
$env = new Core\DotEnv(__DIR__ . '/.env');
$env->load();

// Load configuration
$config = require __DIR__ . '/config/database.php';

// Load database connection
require_once __DIR__ . '/core/Database.php';
use Core\Database;

// Load migration helper
require_once __DIR__ . '/database/Migrator.php';
use Database\Migrator;

// Get database instance with config
$db = Database::getInstance($config);

// Run migrations
$migrator = new Migrator($db);
$migrator->run();

echo "\n✓ Database setup completed successfully!\n";
echo "\nYou can now access the app at: http://localhost:8001\n";
