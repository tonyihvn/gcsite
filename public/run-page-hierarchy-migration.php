<?php
/**
 * Migration Runner - Add Page Hierarchy
 * Visit: http://localhost:8001/run-page-hierarchy-migration.php
 */

// Load environment and database
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../core/DotEnv.php';
require_once __DIR__ . '/../core/Database.php';

// Initialize environment
new \Core\DotEnv(__DIR__);

try {
    // Connect to database
    $dbConfig = require __DIR__ . '/../config/database.php';
    $db = \Core\Database::getInstance($dbConfig)->getConnection();
    
    echo "<h2>🚀 GINTEC Solutions - Database Migration</h2>";
    echo "<p>Adding page hierarchy support (parent_id, menu_order, description)...</p>";
    echo "<hr>";
    
    // Run migrations
    $migrations = [
        "ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `description` TEXT AFTER `title`",
        
        "ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `parent_id` INT NULL AFTER `password`",
        
        "ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `menu_order` INT DEFAULT 0 AFTER `parent_id`",
        
        "ALTER TABLE `gintec_pages` ADD CONSTRAINT `fk_pages_parent` FOREIGN KEY (`parent_id`) REFERENCES `gintec_pages`(`id`) ON DELETE SET NULL",
        
        "ALTER TABLE `gintec_pages` ADD INDEX IF NOT EXISTS idx_parent_id (`parent_id`)",
        
        "ALTER TABLE `gintec_pages` ADD INDEX IF NOT EXISTS idx_menu_order (`menu_order`)"
    ];
    
    foreach ($migrations as $index => $sql) {
        try {
            $db->exec($sql);
            echo "<p style='color: green;'>✓ Migration " . ($index + 1) . " completed successfully</p>";
        } catch (Exception $e) {
            $error = $e->getMessage();
            // Check if it's a duplicate column or constraint error (which is fine)
            if (strpos($error, 'Duplicate') !== false) {
                echo "<p style='color: blue;'>ℹ Migration " . ($index + 1) . ": Column/Key already exists (skipped)</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Migration " . ($index + 1) . ": " . $error . "</p>";
            }
        }
    }
    
    // Verify schema
    echo "<hr>";
    echo "<h3>Database Schema Verification:</h3>";
    echo "<pre>";
    
    $result = $db->query("DESCRIBE `gintec_pages`");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Current columns in gintec_pages table:\n";
    echo str_pad('Field', 20) . str_pad('Type', 30) . str_pad('Null', 10) . str_pad('Key', 10) . "\n";
    echo str_repeat('-', 70) . "\n";
    
    foreach ($columns as $col) {
        echo str_pad($col['Field'], 20) . str_pad($col['Type'], 30) . str_pad($col['Null'], 10) . str_pad($col['Key'] ?? '', 10) . "\n";
    }
    
    echo "</pre>";
    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✓ All migrations completed! Page hierarchy feature is now active.</p>";
    echo "<p><a href='http://localhost:8001/admin/pages/create'>Go to Create New Page →</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Migration Failed</h2>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<pre>";
    echo $e->getTraceAsString();
    echo "</pre>";
}
?>
