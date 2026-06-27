<?php
/**
 * Migration Runner - Add Password Reset Fields
 * Visit: http://localhost:8001/run-migration.php
 */

// Load environment and database
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/core/DotEnv.php';
require_once __DIR__ . '/core/Database.php';

// Initialize environment
new \Core\DotEnv(__DIR__);

try {
    // Connect to database
    $db = new \Core\Database();
    
    echo "<h2>🚀 GINTEC Solutions - Database Migration</h2>";
    echo "<p>Adding password reset fields to users table...</p>";
    echo "<hr>";
    
    // Run migrations
    $migrations = [
        "ALTER TABLE `gintec_users` 
         ADD COLUMN IF NOT EXISTS `reset_token` VARCHAR(255),
         ADD COLUMN IF NOT EXISTS `reset_token_expires_at` TIMESTAMP NULL;",
        
        "ALTER TABLE `gintec_users` 
         ADD INDEX IF NOT EXISTS idx_reset_token (`reset_token`);"
    ];
    
    foreach ($migrations as $index => $sql) {
        try {
            $db->query($sql);
            echo "<p style='color: green;'>✓ Migration " . ($index + 1) . " completed successfully</p>";
        } catch (Exception $e) {
            echo "<p style='color: orange;'>⚠ Migration " . ($index + 1) . ": " . $e->getMessage() . "</p>";
        }
    }
    
    // Verify schema
    echo "<hr>";
    echo "<h3>Database Schema Verification:</h3>";
    echo "<pre>";
    
    $result = $db->query("DESCRIBE `gintec_users`");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Current columns in gintec_users table:\n";
    foreach ($columns as $col) {
        $marker = (in_array($col['Field'], ['reset_token', 'reset_token_expires_at'])) ? "✓ " : "  ";
        echo $marker . "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
    
    echo "</pre>";
    
    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✅ Migration completed! Password reset feature is ready to use.</p>";
    echo "<p><a href='http://localhost:8001/auth/forgot-password'>Go to Forgot Password Page →</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database credentials in .env file</p>";
}
?>
