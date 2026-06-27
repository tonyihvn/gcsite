<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "REQUEST_PATH from parse_url: " . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "\n";

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptBase = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
echo "Script Base: " . $scriptBase . "\n";

$requestPath = str_replace($scriptBase, '', $requestPath);
echo "After str_replace: " . $requestPath . "\n";

$requestPath = trim($requestPath, '/');
echo "Final requestPath: '" . $requestPath . "'\n";

if (empty($requestPath)) {
    echo "✓ Empty path detected - would route to home/index\n";
} else {
    echo "✗ Non-empty path: " . $requestPath . "\n";
}
