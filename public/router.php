<?php
/**
 * Router for PHP Development Server
 * Handles all requests and routes them through index.php
 */

$requested_file = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// If it's a real file or directory, serve it directly
if (file_exists(__DIR__ . $requested_file) && is_file(__DIR__ . $requested_file)) {
    return false;
}

// For directories, check if index.php exists
if (is_dir(__DIR__ . $requested_file)) {
    if (file_exists(__DIR__ . $requested_file . '/index.php')) {
        require __DIR__ . $requested_file . '/index.php';
        return true;
    }
}

// Otherwise, route through index.php
require __DIR__ . '/index.php';
