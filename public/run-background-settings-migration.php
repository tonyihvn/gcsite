<?php
/**
 * Migration Runner - Add Background Settings Columns
 * Visit: http://localhost:8001/run-background-settings-migration.php
 */

// Load config and database
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Database.php';

try {
    // Get database instance with config
    $config = require __DIR__ . '/../config/database.php';
    $db = \Core\Database::getInstance($config);
    $connection = $db->getConnection();
    
    echo "<h2>🚀 GINTEC Solutions - Database Migration</h2>";
    echo "<p>Adding background settings columns (position, size, repeat, attachment) to theme_settings...</p>";
    echo "<hr>";
    
    $migrations = [
        "ALTER TABLE `gintec_theme_settings` ADD COLUMN IF NOT EXISTS `bg_position` VARCHAR(50) DEFAULT 'center'",
        
        "ALTER TABLE `gintec_theme_settings` ADD COLUMN IF NOT EXISTS `bg_size` VARCHAR(50) DEFAULT 'cover'",
        
        "ALTER TABLE `gintec_theme_settings` ADD COLUMN IF NOT EXISTS `bg_repeat` VARCHAR(50) DEFAULT 'no-repeat'",
        
        "ALTER TABLE `gintec_theme_settings` ADD COLUMN IF NOT EXISTS `bg_attachment` VARCHAR(50) DEFAULT 'fixed'",
    ];
    
    foreach ($migrations as $index => $sql) {
        try {
            $connection->exec($sql);
            echo "<p style='color: green;'>✓ Migration " . ($index + 1) . " completed successfully</p>";
        } catch (Exception $e) {
            $error = $e->getMessage();
            if (strpos($error, 'Duplicate') !== false || strpos($error, 'already exists') !== false) {
                echo "<p style='color: blue;'>ℹ Migration " . ($index + 1) . ": Column already exists (skipped)</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Migration " . ($index + 1) . ": " . $error . "</p>";
            }
        }
    }
    
    echo "<hr>";
    echo "<h3>Database Schema Verification:</h3>";
    echo "<pre>";
    
    $result = $connection->query("DESCRIBE `gintec_theme_settings`");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo "✓ " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
    echo "</pre>";
    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✅ All migrations completed successfully!</p>";
    echo "<p><a href='http://localhost:8001/admin/theme'>👉 Go to Theme Settings</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
