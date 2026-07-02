<?php
/**
 * Simple Diagnostics Page - Check Upload System Status
 */

// Security check
$allowed_token = 'gintec-super-secret-key-development-only-change-in-production';
$token = $_GET['token'] ?? '';

if ($token !== $allowed_token) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>GINTEC - Upload Diagnostics</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; }
        .ok { background: #d4edda; border-left: 4px solid #28a745; padding: 12px; margin: 10px 0; border-radius: 4px; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; padding: 12px; margin: 10px 0; border-radius: 4px; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin: 10px 0; border-radius: 4px; }
        code { background: #f4f4f4; padding: 4px 8px; border-radius: 3px; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #ddd; }
        th { background: #f4f4f4; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 GINTEC Upload Diagnostics</h1>
        
        <?php
            // Check directories
            $dirs = [
                'products' => __DIR__ . '/assets/uploads/products',
                'services' => __DIR__ . '/assets/uploads/services',
                'slides' => __DIR__ . '/assets/uploads/slides',
            ];

            echo "<h2>📁 Directory Status</h2>";
            echo "<table>";
            echo "<tr><th>Directory</th><th>Exists</th><th>Writable</th><th>Permissions</th></tr>";
            
            foreach ($dirs as $name => $path) {
                $exists = is_dir($path);
                $writable = $exists && is_writable($path);
                $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
                
                echo "<tr>";
                echo "<td><code>$name/</code></td>";
                echo "<td>" . ($exists ? "✓ Yes" : "✗ No") . "</td>";
                echo "<td>" . ($writable ? "✓ Yes" : "✗ No") . "</td>";
                echo "<td><code>$perms</code></td>";
                echo "</tr>";
            }
            echo "</table>";

            // Test upload
            echo "<h2>🧪 Test Upload</h2>";
            echo '<form method="POST" enctype="multipart/form-data">';
            echo '<input type="file" name="test_file" accept="image/*" required>';
            echo '<select name="test_dir">';
            echo '<option value="">Select directory to test</option>';
            echo '<option value="slides">Slides</option>';
            echo '<option value="services">Services</option>';
            echo '<option value="products">Products</option>';
            echo '</select>';
            echo '<button type="submit">Test Upload</button>';
            echo '</form>';

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
                $file = $_FILES['test_file'];
                $dir = $_POST['test_dir'] ?? '';
                $path = __DIR__ . '/assets/uploads/' . $dir;

                // Try to create directory if it doesn't exist
                if (!is_dir($path)) {
                    if (@mkdir($path, 0755, true)) {
                        echo '<div class="ok">✓ Created directory: ' . htmlspecialchars($dir) . '</div>';
                    } else {
                        echo '<div class="error">✗ Failed to create directory: ' . htmlspecialchars($dir) . '</div>';
                        $path = null;
                    }
                }

                if ($path && is_writable($path)) {
                    $filename = 'test_' . time() . '.jpg';
                    $filepath = $path . '/' . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $filepath)) {
                        @unlink($filepath);
                        echo '<div class="ok">✅ Test Upload Successful!<br>Directory <code>' . htmlspecialchars($dir) . '</code> is working correctly.</div>';
                    } else {
                        echo '<div class="error">✗ Failed to move uploaded file.</div>';
                    }
                } else {
                    echo '<div class="error">✗ Directory not writable or doesn\'t exist.</div>';
                }
            }
        ?>
    </div>
</body>
</html>

