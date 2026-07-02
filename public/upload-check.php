<?php
/**
 * Upload Diagnostic Script
 * Place in public_html and access via https://gintec.com.ng/upload-check.php
 * DELETE THIS FILE after debugging - it exposes server info.
 */

header('Content-Type: text/plain');

echo "=== GINTEC UPLOAD DIAGNOSTIC ===\n\n";

// 1. Script location
echo "1. PATHS\n";
echo "   __DIR__ .............. " . __DIR__ . "\n";
echo "   SCRIPT_FILENAME ...... " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "   dirname(SCRIPT) ...... " . dirname($_SERVER['SCRIPT_FILENAME']) . "\n";
echo "   DOCUMENT_ROOT ........ " . ($_SERVER['DOCUMENT_ROOT'] ?? 'n/a') . "\n\n";

// 2. Upload directory (what FileUploader computes)
$uploadDir = dirname($_SERVER['SCRIPT_FILENAME']) . '/assets/uploads';
echo "2. UPLOAD DIRECTORY\n";
echo "   Computed upload dir .. $uploadDir\n";
echo "   Exists? .............. " . (is_dir($uploadDir) ? 'YES' : 'NO') . "\n";
echo "   Writable? ............ " . (is_writable($uploadDir) ? 'YES' : 'NO') . "\n";
$parent = dirname($uploadDir);
echo "   Parent ($parent)\n";
echo "     Exists? ............ " . (is_dir($parent) ? 'YES' : 'NO') . "\n";
echo "     Writable? .......... " . (is_writable($parent) ? 'YES' : 'NO') . "\n\n";

// 3. Try to create the directory
echo "3. DIRECTORY CREATION TEST\n";
if (!is_dir($uploadDir)) {
    if (@mkdir($uploadDir, 0755, true)) {
        echo "   Created upload dir successfully.\n";
    } else {
        $err = error_get_last();
        echo "   FAILED to create: " . ($err['message'] ?? 'unknown error') . "\n";
    }
} else {
    echo "   Directory already exists.\n";
}
echo "\n";

// 4. Try to write a test file
echo "4. FILE WRITE TEST\n";
$testFile = $uploadDir . '/_write_test_' . time() . '.txt';
if (@file_put_contents($testFile, 'test') !== false) {
    echo "   Wrote test file OK: $testFile\n";
    @unlink($testFile);
    echo "   Removed test file OK.\n";
} else {
    $err = error_get_last();
    echo "   FAILED to write: " . ($err['message'] ?? 'unknown error') . "\n";
}
echo "\n";

// 5. Subdirectories
echo "5. SUBDIRECTORIES\n";
foreach (['slides', 'services', 'products', 'images', 'other'] as $sub) {
    $p = $uploadDir . '/' . $sub;
    $status = is_dir($p) ? (is_writable($p) ? 'exists, writable' : 'exists, NOT writable') : 'MISSING';
    echo "   $sub ............. $status\n";
}
echo "\n";

// 6. PHP upload settings
echo "6. PHP UPLOAD SETTINGS\n";
echo "   file_uploads ......... " . (ini_get('file_uploads') ? 'On' : 'OFF') . "\n";
echo "   upload_max_filesize .. " . ini_get('upload_max_filesize') . "\n";
echo "   post_max_size ........ " . ini_get('post_max_size') . "\n";
echo "   upload_tmp_dir ....... " . (ini_get('upload_tmp_dir') ?: 'default (system tmp)') . "\n";
echo "   max_file_uploads ..... " . ini_get('max_file_uploads') . "\n";
echo "   memory_limit ......... " . ini_get('memory_limit') . "\n\n";

// 7. tmp dir writability
echo "7. TEMP DIR\n";
$tmp = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
echo "   tmp dir .............. $tmp\n";
echo "   Writable? ............ " . (is_writable($tmp) ? 'YES' : 'NO') . "\n\n";

// 8. Current user
echo "8. PROCESS USER\n";
if (function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
    $u = posix_getpwuid(posix_geteuid());
    echo "   PHP running as ....... " . ($u['name'] ?? 'unknown') . "\n";
} else {
    echo "   (posix functions unavailable)\n";
}
echo "\n=== END DIAGNOSTIC ===\n";
echo "\nRemember to DELETE this file when done.\n";
