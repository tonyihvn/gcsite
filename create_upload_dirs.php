<?php
/**
 * Create upload directories for file uploads via HTTP
 * This script ensures all necessary upload directories exist with proper permissions
 * 
 * Usage: https://gintec.com.ng/create_upload_dirs.php?token=YOUR_APP_KEY
 */

// Load environment variables if available
if (file_exists(__DIR__ . '/.env')) {
    $env_file = __DIR__ . '/.env';
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value, '"\'');
        }
    }
}

// Security: Check for valid token in query parameter
$app_key = $_ENV['APP_KEY'] ?? 'gintec-super-secret-key-development-only-change-in-production';
$provided_token = $_GET['token'] ?? '';

// Simple HTML response based on request method
$isHttp = isset($_SERVER['REQUEST_METHOD']);
$html = $isHttp;

if (!$provided_token || $provided_token !== $app_key) {
    if ($html) {
        http_response_code(401);
        echo "<!DOCTYPE html>
<html>
<head><title>Unauthorized</title><style>body{font-family:Arial;margin:20px;}</style></head>
<body>
<h2>❌ Unauthorized Access</h2>
<p>Invalid or missing token. Please provide the correct APP_KEY as token parameter.</p>
<p><strong>Usage:</strong> https://gintec.com.ng/create_upload_dirs.php?token=YOUR_APP_KEY</p>
</body>
</html>";
    } else {
        echo "[ERROR] Invalid token. Provide correct APP_KEY as token parameter.\n";
    }
    exit(1);
}

$dirs = ['slides', 'blog', 'products', 'services', 'pages', 'about', 'partners', 'team', 'images'];
$results = [];
$errors = [];

// Determine base directory
if (strpos(__FILE__, 'public') !== false) {
    $base = dirname(__FILE__) . '/assets/uploads';
} else {
    $base = __DIR__ . '/public/assets/uploads';
}

// Ensure base directory exists with proper permissions
if (!is_dir($base)) {
    if (mkdir($base, 0755, true)) {
        $results[] = "✓ Created base directory";
        chmod($base, 0755);
    } else {
        $errors[] = "✗ Failed to create base directory: $base";
    }
} else {
    $results[] = "✓ Base directory exists";
    // Ensure proper permissions on existing directory
    if (chmod($base, 0755)) {
        $results[] = "✓ Set permissions on base directory (755)";
    }
}

// Create subdirectories
foreach ($dirs as $dir) {
    $path = $base . '/' . $dir;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            $results[] = "✓ Created directory: $dir";
            chmod($path, 0755);
        } else {
            $errors[] = "✗ Failed to create directory: $dir";
        }
    } else {
        $results[] = "✓ Directory already exists: $dir";
        // Ensure proper permissions
        if (chmod($path, 0755)) {
            $results[] = "✓ Permissions verified: $dir (755)";
        }
    }
}

// Output response
if ($html) {
    $icon = empty($errors) ? '✓' : '⚠';
    echo "<!DOCTYPE html>
<html>
<head>
    <title>Upload Directories Setup</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #0369a1; padding-bottom: 10px; }
        .success { color: #10b981; padding: 8px 12px; margin: 5px 0; background: #f0fdf4; border-left: 4px solid #10b981; border-radius: 4px; }
        .error { color: #ef4444; padding: 8px 12px; margin: 5px 0; background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 4px; }
        .info { color: #0369a1; padding: 8px 12px; margin: 10px 0; background: #f0f7ff; border-left: 4px solid #0369a1; border-radius: 4px; }
        .timestamp { color: #999; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
<div class='container'>
    <h1>$icon Upload Directories Setup</h1>
    <div class='info'>
        <strong>Base Directory:</strong><br>
        $base
    </div>";

    foreach ($results as $msg) {
        echo "<div class='success'>$msg</div>";
    }

    foreach ($errors as $msg) {
        echo "<div class='error'>$msg</div>";
    }

    echo "<div class='info'><strong>Status:</strong> " . (empty($errors) ? "✓ All directories ready!" : "⚠ Some issues encountered") . "</div>";
    echo "<p class='timestamp'>Generated: " . date('Y-m-d H:i:s') . "</p>";
    echo "</div>
</body>
</html>";
} else {
    // CLI output
    echo "\n=== Upload Directories Setup ===\n";
    echo "Base: $base\n\n";
    foreach ($results as $msg) {
        echo "$msg\n";
    }
    foreach ($errors as $msg) {
        echo "$msg\n";
    }
    echo "\n✓ Setup complete!\n";
}

