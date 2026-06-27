<?php
/**
 * Create Admin User Script
 * Run this script once to create an admin account
 */

$APP_ROOT = dirname(dirname(__FILE__));

// Load environment and config
require_once $APP_ROOT . '/core/DotEnv.php';
$env = new \Core\DotEnv($APP_ROOT . '/.env');

require_once $APP_ROOT . '/config/database.php';
require_once $APP_ROOT . '/core/Database.php';
require_once $APP_ROOT . '/core/Security.php';

$config = require $APP_ROOT . '/config/database.php';
$db = \Core\Database::getInstance($config);

// Admin credentials
$email = 'admin@gintec.com.ng';
$password = 'Admin@123456'; // Change this password!
$first_name = 'Admin';
$last_name = 'User';

// Hash password
$hashedPassword = \Core\Security::hashPassword($password);

try {
    // Check if admin already exists
    $stmt = $db->prepare('SELECT id FROM gintec_users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo "✗ Admin user already exists with email: $email\n";
        exit;
    }
    
    // Create admin user
    $sql = "INSERT INTO gintec_users (first_name, last_name, email, password, role, status, email_verified_at, created_at) 
            VALUES (?, ?, ?, ?, 'admin', 'active', NOW(), NOW())";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$first_name, $last_name, $email, $hashedPassword]);
    
    echo "✅ Admin user created successfully!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Email:    $email\n";
    echo "Password: $password\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "⚠️  Change this password after first login!\n";
    echo "\nLogin at: http://localhost:8001/auth/login\n";
    
} catch (\Exception $e) {
    echo "✗ Error creating admin user: " . $e->getMessage() . "\n";
}
?>
