<?php
/**
 * Setup Upload Directories - Web Accessible Version
 * Place in /public directory
 * 
 * Access via: https://gintec.com.ng/setup-uploads.php?token=YOUR_APP_KEY
 */

// Load environment variables from parent directory
if (file_exists(__DIR__ . '/../.env')) {
    $env_file = __DIR__ . '/../.env';
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

if (!$provided_token || $provided_token !== $app_key) {
    http_response_code(401);
    echo "<!DOCTYPE html>
<html>
<head><title>Unauthorized</title><style>body{font-family:Arial;margin:20px;color:#333;}</style></head>
<body>
<h2>❌ Unauthorized Access</h2>
<p>Invalid or missing security token.</p>
<p><strong>Usage:</strong><br>
https://gintec.com.ng/setup-uploads.php?token=YOUR_APP_KEY</p>
<p style='color:#999; font-size:12px;'>The token is your APP_KEY from .env file</p>
</body>
</html>";
    exit(1);
}

$dirs = ['slides', 'blog', 'products', 'services', 'pages', 'about', 'partners', 'team', 'images'];
$results = [];
$errors = [];

// Detect if we're on shared hosting (gcsite/public structure)
$currentDir = __DIR__;
$isSharedHosting = strpos($currentDir, 'gcsite') !== false;

if ($isSharedHosting) {
    // Shared hosting: upload to /public_html/uploads/
    $publicHtmlRoot = dirname(dirname($currentDir));
    $base = $publicHtmlRoot . '/uploads';
} else {
    // Local development: upload to public/assets/uploads
    $base = __DIR__ . '/assets/uploads';
}

// Ensure base directory exists
if (!is_dir($base)) {
    if (mkdir($base, 0755, true)) {
        $results[] = "✓ Created base directory";
        chmod($base, 0755);
    } else {
        $errors[] = "✗ Failed to create base directory";
    }
} else {
    $results[] = "✓ Base directory exists";
    chmod($base, 0755);
}

// Create subdirectories
foreach ($dirs as $dir) {
    $path = $base . '/' . $dir;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            $results[] = "✓ Created: <code>$dir/</code>";
            chmod($path, 0755);
        } else {
            $errors[] = "✗ Failed to create: <code>$dir/</code>";
        }
    } else {
        $results[] = "✓ Exists: <code>$dir/</code>";
        chmod($path, 0755);
    }
}

$success = empty($errors);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Directories Setup</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .status-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        h1 {
            color: #222;
            font-size: 28px;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 12px;
        }
        .status-badge.success {
            background: #d1fae5;
            color: #065f46;
        }
        .status-badge.warning {
            background: #fef3c7;
            color: #92400e;
        }
        .message-list {
            margin: 25px 0;
        }
        .message {
            padding: 12px 15px;
            margin: 8px 0;
            border-radius: 6px;
            border-left: 4px solid;
            font-size: 13px;
            line-height: 1.5;
        }
        .message.success {
            background: #f0fdf4;
            border-color: #10b981;
            color: #065f46;
        }
        .message.error {
            background: #fef2f2;
            border-color: #ef4444;
            color: #991b1b;
        }
        .message code {
            background: rgba(0,0,0,0.05);
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Monaco', 'Courier New', monospace;
        }
        .info-box {
            background: #f0f7ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 15px;
            margin-top: 25px;
            font-size: 12px;
            color: #1e40af;
        }
        .info-box strong {
            display: block;
            margin-bottom: 8px;
            color: #1e3a8a;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #999;
            font-size: 11px;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="status-icon"><?php echo $success ? '✅' : '⚠️'; ?></div>
            <h1>Upload Directories Setup</h1>
            <p class="subtitle">GINTEC Solutions</p>
            <div class="status-badge <?php echo $success ? 'success' : 'warning'; ?>">
                <?php echo $success ? '✓ READY' : '⚠ PARTIAL'; ?>
            </div>
        </div>

        <div class="message-list">
            <?php foreach ($results as $msg): ?>
                <div class="message success"><?php echo $msg; ?></div>
            <?php endforeach; ?>
            
            <?php foreach ($errors as $msg): ?>
                <div class="message error"><?php echo $msg; ?></div>
            <?php endforeach; ?>
        </div>

        <div class="info-box">
            <strong>📁 Base Directory:</strong>
            <?php echo htmlspecialchars($base); ?>
        </div>

        <div class="info-box">
            <strong>✓ Directories Created:</strong>
            <?php echo implode(', ', array_map(fn($d) => "<code>$d/</code>", $dirs)); ?>
        </div>

        <div class="footer">
            <strong>Status:</strong> <?php echo date('Y-m-d H:i:s (T)'); ?><br>
            All upload directories are now ready for file uploads
        </div>
    </div>
</body>
</html>
