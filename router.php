<?php
/**
 * Router for PHP Development Server
 * Serves static files directly and routes PHP requests to index.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// List of static file extensions
$staticExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'ico', 'woff', 'woff2', 'ttf', 'eot'];

// Get file extension
$path = $_SERVER['REQUEST_URI'];
if (preg_match('/\.(' . implode('|', $staticExtensions) . ')$/i', $path)) {
    // Serve static files directly
    $file = __DIR__ . '/public' . $uri;
    
    if (file_exists($file)) {
        return false; // Serve the file
    }
}

// Route all other requests to index.php
require_once __DIR__ . '/public/index.php';
