<?php
/**
 * Create upload directories for file uploads
 * This script ensures all necessary upload directories exist with proper permissions
 */

$dirs = ['slides', 'blog', 'products', 'services', 'pages', 'about', 'partners', 'team', 'images'];

// Use the public directory as base
$base = __DIR__ . '/public/assets/uploads';

// Ensure base directory exists with proper permissions
if (!is_dir($base)) {
    if (mkdir($base, 0755, true)) {
        echo "[OK] Created base directory: $base\n";
        chmod($base, 0755);
    } else {
        echo "[ERROR] Failed to create base directory: $base\n";
        exit(1);
    }
} else {
    echo "[OK] Base directory exists: $base\n";
    // Ensure proper permissions on existing directory
    chmod($base, 0755);
}

// Create subdirectories
foreach ($dirs as $dir) {
    $path = $base . '/' . $dir;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            echo "[OK] Created: $path\n";
            chmod($path, 0755);
        } else {
            echo "[ERROR] Failed to create: $path\n";
        }
    } else {
        echo "[OK] Already exists: $path\n";
        // Ensure proper permissions
        chmod($path, 0755);
    }
}

echo "\n✓ Upload directories setup complete!\n";
echo "Base upload directory: $base\n";

