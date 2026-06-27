<?php
/**
 * Migration Runner - Add Brochure and Proposal Fields
 * Visit: http://localhost:8001/run-brochure-proposal-migration.php
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Database.php';

try {
    $config = require __DIR__ . '/../config/database.php';
    $db = \Core\Database::getInstance($config);
    $connection = $db->getConnection();
    
    echo "<h2>🚀 GINTEC Solutions - Add Brochure & Proposal Fields</h2>";
    echo "<p>Adding brochure and proposal upload fields to products and services...</p>";
    echo "<hr>";
    
    $migrations = [
        "Add brochure_url to products" => "ALTER TABLE `gintec_products` ADD COLUMN IF NOT EXISTS `brochure_url` VARCHAR(255) AFTER `image_url`",
        "Add proposal_url to products" => "ALTER TABLE `gintec_products` ADD COLUMN IF NOT EXISTS `proposal_url` VARCHAR(255) AFTER `brochure_url`",
        "Add brochure_url to services" => "ALTER TABLE `gintec_services` ADD COLUMN IF NOT EXISTS `brochure_url` VARCHAR(255) AFTER `image_url`",
        "Add proposal_url to services" => "ALTER TABLE `gintec_services` ADD COLUMN IF NOT EXISTS `proposal_url` VARCHAR(255) AFTER `brochure_url`",
    ];
    
    $completed = 0;
    foreach ($migrations as $name => $sql) {
        try {
            $connection->exec($sql);
            echo "<p style='color: green;'>✓ " . $name . " completed</p>";
            $completed++;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false || strpos($e->getMessage(), 'already exists') !== false) {
                echo "<p style='color: blue;'>ℹ " . $name . ": Already exists (skipped)</p>";
            } else {
                echo "<p style='color: orange;'>⚠ " . $name . ": " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<hr>";
    echo "<h3>Database Schema Verification:</h3>";
    echo "<pre>";
    
    // Verify products table
    try {
        $result = $connection->query("DESCRIBE `gintec_products`");
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);
        echo "Products Table (Download fields):\n";
        foreach ($columns as $column) {
            if (in_array($column['Field'], ['brochure_url', 'proposal_url'])) {
                echo "✓ " . $column['Field'] . " (" . $column['Type'] . ")\n";
            }
        }
        echo "\n";
    } catch (Exception $e) {
        echo "Could not describe products table\n";
    }
    
    // Verify services table
    try {
        $result = $connection->query("DESCRIBE `gintec_services`");
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);
        echo "Services Table (Download fields):\n";
        foreach ($columns as $column) {
            if (in_array($column['Field'], ['brochure_url', 'proposal_url'])) {
                echo "✓ " . $column['Field'] . " (" . $column['Type'] . ")\n";
            }
        }
    } catch (Exception $e) {
        echo "Could not describe services table\n";
    }
    
    echo "</pre>";
    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✅ Migration completed!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
