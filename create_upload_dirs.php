<?php
/**
 * Create upload directories for file uploads
 */

$dirs = ['slides', 'blog', 'products', 'services', 'pages', 'about', 'partners'];
$base = __DIR__ . '/public/assets/uploads';

// Ensure base directory exists
if (!is_dir($base)) {
    mkdir($base, 0755, true);
    echo "Created base directory: $base\n";
}

// Create subdirectories
foreach ($dirs as $dir) {
    $path = $base . '/' . $dir;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
        echo "Created: $path\n";
    } else {
        echo "Already exists: $path\n";
    }
}

echo "\nUpload directories setup complete!\n";
