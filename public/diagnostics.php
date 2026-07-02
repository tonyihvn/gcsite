<?php
/**
 * Diagnostics Page - Check Upload System Status
 * Remove after debugging
 */

// Security check
$allowed_token = 'gintec-super-secret-key-development-only-change-in-production';
$token = $_GET['token'] ?? '';

if ($token !== $allowed_token) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

// Load env
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value, '"\'');
        }
    }
}

// Detect environment
$currentDir = __DIR__;
$parentDir = dirname($currentDir);
$possibleRoots = [
    $parentDir . '/gcsite',
    dirname($parentDir) . '/gcsite',
    $parentDir,
];

$appRoot = null;
foreach ($possibleRoots as $testPath) {
    if (is_dir($testPath) && file_exists($testPath . '/app') && file_exists($testPath . '/core')) {
        $appRoot = $testPath;
        break;
    }
}

if ($appRoot === null) {
    $appRoot = $parentDir;
}

define('PUBLIC_PATH', $currentDir);
define('APP_ROOT', $appRoot);

// Check directories
$dirs = [
    'public/assets/uploads' => __DIR__ . '/assets/uploads',
    'public/assets/uploads/slides' => __DIR__ . '/assets/uploads/slides',
    'public/assets/uploads/services' => __DIR__ . '/assets/uploads/services',
    'public/assets/uploads/products' => __DIR__ . '/assets/uploads/products',
];

// Check error log
$errorLogPath = dirname($currentDir) . '/error_log';
if (!file_exists($errorLogPath)) {
    $errorLogPath = '/var/log/php-errors.log';
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>GINTEC - Upload System Diagnostics</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; }
        .status { margin: 20px 0; padding: 15px; border-radius: 4px; }
        .ok { background: #d4edda; border-left: 4px solid #28a745; color: #155724; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; color: #721c24; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; color: #856404; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        .section { margin: 30px 0; }
        .section h2 { border-bottom: 2px solid #0369a1; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #ddd; }
        th { background: #f4f4f4; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 GINTEC Upload System Diagnostics</h1>
        
        <div class="section">
            <h2>Environment</h2>
            <table>
                <tr>
                    <td><strong>App Root:</strong></td>
                    <td><code><?= htmlspecialchars(APP_ROOT) ?></code></td>
                </tr>
                <tr>
                    <td><strong>Public Path:</strong></td>
                    <td><code><?= htmlspecialchars(PUBLIC_PATH) ?></code></td>
                </tr>
                <tr>
                    <td><strong>APP_URL:</strong></td>
                    <td><code><?= htmlspecialchars($_ENV['APP_URL'] ?? 'Not set') ?></code></td>
                </tr>
                <tr>
                    <td><strong>APP_ENV:</strong></td>
                    <td><code><?= htmlspecialchars($_ENV['APP_ENV'] ?? 'Not set') ?></code></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2>📁 Directory Status</h2>
            <?php foreach ($dirs as $name => $path): ?>
                <?php $exists = is_dir($path); ?>
                <?php $writable = $exists && is_writable($path); ?>
                <?php $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A'; ?>
                <div class="status <?= $exists ? ($writable ? 'ok' : 'warning') : 'error' ?>">
                    <strong><?= $exists ? '✓' : '✗' ?> <?= htmlspecialchars($name) ?></strong><br>
                    Path: <code><?= htmlspecialchars($path) ?></code><br>
                    Permissions: <code><?= $perms ?></code>
                    <?php if (!$writable && $exists): ?>
                        <br><span style="color: #856404;">⚠ Directory exists but not writable</span>
                    <?php elseif (!$exists): ?>
                        <br><span style="color: #721c24;">✗ Directory does not exist</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="section">
            <h2>📝 Recent Errors</h2>
            <?php if (file_exists($errorLogPath) && is_readable($errorLogPath)): ?>
                <?php 
                    $lines = file_tail($errorLogPath, 50);
                    $uploadErrors = array_filter($lines, function($l) { 
                        return stripos($l, 'upload') !== false || stripos($l, 'permission') !== false || stripos($l, 'mkdir') !== false;
                    });
                ?>
                <?php if (!empty($uploadErrors)): ?>
                    <pre style="background: #f4f4f4; padding: 15px; border-radius: 4px; overflow-x: auto; max-height: 300px;">
<?php echo htmlspecialchars(implode("\n", array_slice(array_reverse($uploadErrors), 0, 20))); ?>
                    </pre>
                <?php else: ?>
                    <div class="status ok">✓ No upload-related errors found in recent logs</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="status warning">⚠ Error log not accessible at <?= htmlspecialchars($errorLogPath) ?></div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>🧪 Test Upload</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="test_file" accept="image/*" required style="padding: 10px; margin: 10px 0;">
                <select name="test_subdir" required style="padding: 8px; margin: 10px 0;">
                    <option value="">Select directory to test</option>
                    <option value="slides">Slides</option>
                    <option value="services">Services</option>
                    <option value="products">Products</option>
                </select>
                <button type="submit" style="padding: 8px 16px; background: #0369a1; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Test Upload
                </button>
            </form>

            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])): ?>
                <?php 
                    $file = $_FILES['test_file'];
                    $subdir = $_POST['test_subdir'] ?? '';
                    $uploadDir = __DIR__ . '/assets/uploads/' . $subdir;
                    $testResult = [];
                    
                    // Check if directory exists
                    if (!is_dir($uploadDir)) {
                        $testResult['error'] = "Directory does not exist: $uploadDir";
                        if (@mkdir($uploadDir, 0755, true)) {
                            $testResult['info'][] = "Created directory: $uploadDir";
                        } else {
                            $testResult['error'] = "Failed to create directory: $uploadDir";
                        }
                    }
                    
                    if (!isset($testResult['error'])) {
                        if (is_writable($uploadDir)) {
                            $filename = 'test_' . time() . '_' . basename($file['name']);
                            $filepath = $uploadDir . '/' . $filename;
                            
                            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                                $testResult['success'] = "✓ Test file uploaded successfully!";
                                $testResult['info'][] = "File: " . htmlspecialchars($filename);
                                $testResult['info'][] = "Path: " . htmlspecialchars($filepath);
                                @unlink($filepath); // Clean up
                            } else {
                                $testResult['error'] = "Failed to move uploaded file";
                            }
                        } else {
                            $testResult['error'] = "Directory not writable: $uploadDir";
                        }
                    }
                ?>
                <div style="margin-top: 20px;">
                    <?php if (isset($testResult['success'])): ?>
                        <div class="status ok">
                            <strong><?= $testResult['success'] ?></strong><br>
                            <?php foreach ($testResult['info'] ?? [] as $info): ?>
                                <?= htmlspecialchars($info) ?><br>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="status error">
                            <strong>✗ Upload Test Failed</strong><br>
                            <?= htmlspecialchars($testResult['error'] ?? 'Unknown error') ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <hr>
        <p style="color: #999; font-size: 12px;">
            <strong>⚠ Security Note:</strong> This diagnostics page is accessible to anyone with the token. 
            Remove this file after debugging.
        </p>
    </div>
</body>
</html>

<?php
function file_tail($file, $lines = 20) {
    $f = @fopen($file, 'rb');
    if ($f === false) return [];
    
    fseek($f, 0, SEEK_END);
    $pos = ftell($f);
    $result = [];
    
    for ($line = 0; $line < $lines; $line++) {
        $pos = ftell($f);
        if ($pos == 0) break;
        
        $seeking = 512;
        if ($pos < $seeking) $seeking = $pos;
        fseek($f, -$seeking, SEEK_CUR);
        
        $chunk = fread($f, $seeking);
        $array = explode("\n", $chunk);
        fseek($f, -strlen($array[0]), SEEK_CUR);
    }
    
    while (!feof($f)) {
        $result[] = fgets($f);
    }
    fclose($f);
    return array_filter($result);
}
?>
