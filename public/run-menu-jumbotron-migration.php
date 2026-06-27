<?php
/**
 * Migration Runner - Create Menus Table and Add Jumbotron Fields
 * Visit: http://localhost:8001/run-menu-jumbotron-migration.php
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
    echo "<p>Creating menus table and adding jumbotron fields to pages...</p>";
    echo "<hr>";
    
    $migrations = [
        "Create menus table" => "CREATE TABLE IF NOT EXISTS `gintec_menus` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `label` VARCHAR(255) NOT NULL,
    `url` VARCHAR(500),
    `icon` VARCHAR(100),
    `parent_id` INT,
    `menu_order` INT DEFAULT 0,
    `status` VARCHAR(20) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`parent_id`) REFERENCES `gintec_menus`(`id`) ON DELETE CASCADE,
    KEY `menu_order_idx` (`menu_order`),
    KEY `parent_id_idx` (`parent_id`),
    KEY `status_idx` (`status`)
)",
        "Add page_header column" => "ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `page_header` TEXT AFTER `description`",
        "Add page_subheader column" => "ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `page_subheader` VARCHAR(500) AFTER `page_header`",
        "Add header_bg_image column" => "ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `header_bg_image` VARCHAR(255) AFTER `page_subheader`",
        "Add header_bg_color column" => "ALTER TABLE `gintec_pages` ADD COLUMN IF NOT EXISTS `header_bg_color` VARCHAR(7) DEFAULT '#f8f9fa' AFTER `header_bg_image`",
        "Insert default menus" => "INSERT IGNORE INTO `gintec_menus` (`id`, `label`, `url`, `icon`, `parent_id`, `menu_order`, `status`) VALUES
(1, 'Home', '/', 'fas fa-home', NULL, 1, 'active'),
(2, 'Services', '/services', 'fas fa-cogs', NULL, 2, 'active'),
(3, 'Products', '/products', 'fas fa-box', NULL, 3, 'active'),
(4, 'About', '/about', 'fas fa-info-circle', NULL, 4, 'active'),
(5, 'Team', '/team', 'fas fa-users', NULL, 5, 'active'),
(6, 'Partners', '/partners', 'fas fa-handshake', NULL, 6, 'active'),
(7, 'Blog', '/blog', 'fas fa-newspaper', NULL, 7, 'active'),
(8, 'Contact', '/contact', 'fas fa-envelope', NULL, 8, 'active')",
    ];
    
    $completed = 0;
    foreach ($migrations as $name => $sql) {
        try {
            $connection->exec($sql);
            echo "<p style='color: green;'>✓ " . $name . " completed</p>";
            $completed++;
        } catch (Exception $e) {
            $error = $e->getMessage();
            if (strpos($error, 'Duplicate') !== false || strpos($error, 'already exists') !== false) {
                echo "<p style='color: blue;'>ℹ " . $name . ": Already exists (skipped)</p>";
            } else {
                echo "<p style='color: orange;'>⚠ " . $name . ": " . $error . "</p>";
            }
        }
    }
    
    echo "<hr>";
    echo "<h3>Database Schema Verification:</h3>";
    echo "<pre>";
    
    // Verify menus table
    try {
        $result = $connection->query("DESCRIBE `gintec_menus`");
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);
        echo "Menus Table:\n";
        foreach ($columns as $column) {
            echo "✓ " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }
        echo "\n";
    } catch (Exception $e) {
        echo "Could not describe menus table\n";
    }
    
    // Verify pages jumbotron columns
    try {
        $result = $connection->query("DESCRIBE `gintec_pages`");
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);
        echo "Pages Table (Jumbotron fields):\n";
        foreach ($columns as $column) {
            if (in_array($column['Field'], ['page_header', 'page_subheader', 'header_bg_image', 'header_bg_color'])) {
                echo "✓ " . $column['Field'] . " (" . $column['Type'] . ")\n";
            }
        }
    } catch (Exception $e) {
        echo "Could not describe pages table\n";
    }
    
    echo "</pre>";
    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✅ Migration completed!</p>";
    echo "<p><a href='http://localhost:8001/admin/menus'>👉 Go to Menu Manager</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
