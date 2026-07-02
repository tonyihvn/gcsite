<?php
namespace Core;

class FileUploader
{
    private $uploadDir;
    private $baseUploadRelativePath = 'uploads'; // Now relative to public_html root
    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private $maxFileSize = 5242880; // 5MB

    public function __construct()
    {
        // Detect environment and determine upload directory
        $currentDir = dirname($_SERVER['SCRIPT_FILENAME']);
        
        // Check if we're in shared hosting (gcsite/public structure)
        if (strpos($currentDir, 'gcsite') !== false) {
            // Shared hosting: go up to public_html root
            // Path: /public_html/gcsite/public -> go up 2 levels to /public_html
            $publicHtmlRoot = dirname(dirname($currentDir));
            
            // Check if uploads folder is in parent directory
            if (is_dir($publicHtmlRoot . '/../uploads')) {
                $this->uploadDir = $publicHtmlRoot . '/../uploads';
            } else {
                // Default to /public_html/uploads
                $this->uploadDir = $publicHtmlRoot . '/uploads';
            }
        } else {
            // Local development: use public/assets/uploads
            $this->uploadDir = $currentDir . '/assets/uploads';
        }
        
        // Ensure upload directory exists with proper permissions
        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0755, true)) {
                error_log('Failed to create upload directory: ' . $this->uploadDir);
            }
        }
    }

    /**
     * Upload a file and return the relative path
     * @param string $inputName - Name of the file input field
     * @param string $subdir - Subdirectory within uploads (e.g., 'slides', 'blog', 'products')
     * @return string|null - Relative path to uploaded file or null if upload failed
     */
    public function upload($inputName, $subdir = '')
    {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // No file uploaded
        }

        $file = $_FILES[$inputName];

        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('File upload error: ' . $this->getUploadErrorMessage($file['error']));
        }

        if ($file['size'] > $this->maxFileSize) {
            throw new \Exception('File size exceeds maximum limit of 5MB');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedExtensions)) {
            throw new \Exception('File type not allowed. Allowed: ' . implode(', ', $this->allowedExtensions));
        }

        // Create subdirectory if specified
        $uploadPath = $this->uploadDir;
        if ($subdir) {
            $uploadPath .= '/' . trim($subdir, '/');
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    error_log("FileUploader: Failed to create directory '$uploadPath'. Parent writable: " . (is_writable(dirname($uploadPath)) ? 'yes' : 'no'));
                    throw new \Exception('Failed to create upload subdirectory: ' . $uploadPath);
                }
                chmod($uploadPath, 0755);
            }
        }

        // Generate unique filename
        $filename = uniqid('img_') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $filepath = $uploadPath . '/' . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Set proper permissions
            chmod($filepath, 0644);
            
            // Log successful upload
            error_log("FileUploader: Successfully uploaded '$file[name]' to '$filepath'");
            
            // Return relative path for storage (relative to public directory)
            $relativePath = $this->baseUploadRelativePath . ($subdir ? '/' . trim($subdir, '/') : '') . '/' . $filename;
            return str_replace('\\', '/', $relativePath);
        }

        error_log("FileUploader: move_uploaded_file failed for '$file[name]' to '$filepath'");
        throw new \Exception('Failed to move uploaded file');
    }

    /**
     * Delete an uploaded file
     * @param string $filepath - Relative path to the file
     * @return bool
     */
    public function delete($filepath)
    {
        if ($filepath && file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }

    /**
     * Get upload error message
     * @param int $errorCode
     * @return string
     */
    private function getUploadErrorMessage($errorCode)
    {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        return $errors[$errorCode] ?? 'Unknown upload error';
    }

    /**
     * Check if a path is an uploaded file (not a URL)
     * @param string $path
     * @return bool
     */
    public static function isUploadedFile($path)
    {
        return $path && (strpos($path, 'uploads/') === 0 || strpos($path, 'assets/uploads/') === 0);
    }

    /**
     * Get display URL for an image
     * @param string $imagePath - File path or URL
     * @param string $default - Default image if path is empty
     * @return string
     */
    public static function getImageUrl($imagePath, $default = 'https://via.placeholder.com/400x300?text=Image')
    {
        if (!$imagePath) {
            return $default;
        }

        // If it's a URL (starts with http), return as-is
        if (strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }

        // If it's an uploaded file, construct the proper URL
        if (self::isUploadedFile($imagePath)) {
            // Normalize path - remove assets/ prefix if present
            $imagePath = str_replace('assets/', '', $imagePath);
            
            // Get the base URL from config if available
            $baseUrl = defined('APP_URL') ? APP_URL : (isset($_ENV['APP_URL']) ? $_ENV['APP_URL'] : '');
            
            if (empty($baseUrl)) {
                // Fallback to protocol + host
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
                $baseUrl = $protocol . $_SERVER['HTTP_HOST'];
            }
            
            // Ensure base URL ends without trailing slash
            $baseUrl = rtrim($baseUrl, '/');
            
            // Construct full URL
            return $baseUrl . '/' . $imagePath;
        }

        // Default fallback
        return $default;
    }
}
