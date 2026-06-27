<?php
// Database Migration: Add missing fields to products and services tables
// Access: http://localhost:8001/run-product-service-migration.php

require_once __DIR__ . '/../config/app.php';
require_once APP_ROOT . '/core/Database.php';

$db = new Database();
$pdo = $db->getConnection();

$migrationResults = [];

// Add icon and website to products table
try {
    $pdo->exec("ALTER TABLE `gintec_products` ADD COLUMN `icon` VARCHAR(255) AFTER `image_url`");
    $migrationResults['products_icon'] = '✓ Added icon column to products table';
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        $migrationResults['products_icon'] = '⚠ Icon column already exists in products table';
    } else {
        $migrationResults['products_icon_error'] = '✗ Error adding icon to products: ' . $e->getMessage();
    }
}

try {
    $pdo->exec("ALTER TABLE `gintec_products` ADD COLUMN `website` VARCHAR(255) AFTER `documentation_url`");
    $migrationResults['products_website'] = '✓ Added website column to products table';
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        $migrationResults['products_website'] = '⚠ Website column already exists in products table';
    } else {
        $migrationResults['products_website_error'] = '✗ Error adding website to products: ' . $e->getMessage();
    }
}

// Add website to services table
try {
    $pdo->exec("ALTER TABLE `gintec_services` ADD COLUMN `website` VARCHAR(255) AFTER `image_url`");
    $migrationResults['services_website'] = '✓ Added website column to services table';
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        $migrationResults['services_website'] = '⚠ Website column already exists in services table';
    } else {
        $migrationResults['services_website_error'] = '✗ Error adding website to services: ' . $e->getMessage();
    }
}

// Verify migration
$stmt = $pdo->query("SHOW COLUMNS FROM `gintec_products`");
$productColumns = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');

$stmt = $pdo->query("SHOW COLUMNS FROM `gintec_services`");
$serviceColumns = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product & Service Fields Migration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 2rem; background-color: #f5f5f5; }
        .card { border-left: 4px solid #0369a1; }
        .card-header { background: linear-gradient(135deg, #0369a1, #0c4a6e); color: white; font-weight: bold; }
        .success { color: #28a745; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-database"></i> Product & Service Fields Migration
            </div>
            <div class="card-body">
                <h5>Migration Results</h5>
                <div class="list-group">
                    <?php foreach ($migrationResults as $key => $message): ?>
                        <div class="list-group-item">
                            <?php 
                                if (strpos($key, 'error') !== false) {
                                    echo "<span class='error'>$message</span>";
                                } elseif (strpos($message, '✓') === 0) {
                                    echo "<span class='success'>$message</span>";
                                } else {
                                    echo "<span class='warning'>$message</span>";
                                }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr>
                <h5>Schema Verification</h5>
                
                <h6>Products Table Columns:</h6>
                <div class="mb-3">
                    <?php if (in_array('icon', $productColumns)): ?>
                        <span class="success">✓ icon</span><br>
                    <?php endif; ?>
                    <?php if (in_array('website', $productColumns)): ?>
                        <span class="success">✓ website</span><br>
                    <?php endif; ?>
                    <?php if (in_array('image_url', $productColumns)): ?>
                        <span class="success">✓ image_url</span><br>
                    <?php endif; ?>
                    <?php if (in_array('demo_url', $productColumns)): ?>
                        <span class="success">✓ demo_url</span><br>
                    <?php endif; ?>
                    <?php if (in_array('documentation_url', $productColumns)): ?>
                        <span class="success">✓ documentation_url</span>
                    <?php endif; ?>
                </div>

                <h6>Services Table Columns:</h6>
                <div>
                    <?php if (in_array('icon', $serviceColumns)): ?>
                        <span class="success">✓ icon</span><br>
                    <?php endif; ?>
                    <?php if (in_array('website', $serviceColumns)): ?>
                        <span class="success">✓ website</span><br>
                    <?php endif; ?>
                    <?php if (in_array('image_url', $serviceColumns)): ?>
                        <span class="success">✓ image_url</span>
                    <?php endif; ?>
                </div>

                <hr>
                <a href="<?= route('admin/products') ?>" class="btn btn-primary">Go to Products</a>
                <a href="<?= route('admin/services') ?>" class="btn btn-secondary">Go to Services</a>
            </div>
        </div>
    </div>
</body>
</html>
