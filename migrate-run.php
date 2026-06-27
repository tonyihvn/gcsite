<?php
/**
 * Database Migration Runner - Standalone
 * Executes all pending migrations from database/migrations/
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get project root
$projectRoot = __DIR__;

// Load environment
require_once $projectRoot . '/core/DotEnv.php';
require_once $projectRoot . '/config/database.php';
require_once $projectRoot . '/core/Database.php';

// Initialize environment
new \Core\DotEnv($projectRoot);

try {
    // Get database configuration
    $dbConfig = require $projectRoot . '/config/database.php';
    
    // Connect to database using singleton
    $db = \Core\Database::getInstance($dbConfig);
    echo "✓ Connected to database\n\n";

    // Array of migration files in order
    $migrations = [
        '001_create_tables.php',
        '004_add_parent_id_to_pages.sql',
        '005_add_theme_settings.sql'
    ];

    $migrationsPath = $projectRoot . '/database/migrations/';
    $pdo = $db->getConnection();

    foreach ($migrations as $file) {
        $filePath = $migrationsPath . $file;
        
        if (!file_exists($filePath)) {
            echo "⚠ Migration file not found: $file\n";
            continue;
        }

        echo "Running migration: $file\n";

        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            // SQL file - read and execute
            $sql = file_get_contents($filePath);
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (\Exception $e) {
                        echo "  Error: " . $e->getMessage() . "\n";
                    }
                }
            }
        } else {
            // PHP file - include it  
            require_once $filePath;
        }

        echo "✓ $file completed\n\n";
    }

    echo "\n✓ All migrations completed successfully!\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
?>

