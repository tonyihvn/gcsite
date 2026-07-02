<?php
namespace Core;

class FileUploader
{
    private $uploadDir;
    private $baseUploadRelativePath = 'assets/uploads'; // Relative to public directory
    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private $maxFileSize = 5242880; // 5MB

    public function __construct()
    {
        // Determine the public directory based on current script location
        $scriptPath = dirname($_SERVER['SCRIPT_FILENAME']);
        error_log('FileUploader: Script path = ' . $scriptPath);
        
        // Use public/assets/uploads relative to the public directory
        // This works for both local development and production shared hosting
        $this->uploadDir = $scriptPath . '/assets/uploads';
        error_log('FileUploader: Upload dir = ' . $this->uploadDir);
        
        // Ensure upload directory exists with proper permissions
        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0755, true)) {
                error_log('FileUploader: FAILED to create upload directory: ' . $this->uploadDir);
            } else {
                error_log('FileUploader: Created upload directory: ' . $this->uploadDir);
                chmod($this->uploadDir, 0755);
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
        if (!isset($_FILES[$inputName])) {
            error_log("FileUploader: $_FILES[$inputName] not set");
            return null;
        }
        
        if ($_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
            error_log("FileUploader: No file uploaded for $inputName");
            return null; // No file uploaded
        }

        $file = $_FILES[$inputName];

        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = 'File upload error: ' . $this->getUploadErrorMessage($file['error']);
            error_log("FileUploader: $errorMsg for input '$inputName'");
            throw new \Exception($errorMsg);
        }

        if ($file['size'] <= 0) {
            error_log("FileUploader: File size is 0 for '$inputName'");
            throw new \Exception('File size is invalid');
        }

        if ($file['size'] > $this->maxFileSize) {
            $msg = 'File size exceeds maximum limit of 5MB (Got: ' . ($file['size'] / 1024 / 1024) . 'MB)';
            error_log("FileUploader: $msg");
            throw new \Exception($msg);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedExtensions)) {
            $msg = 'File type not allowed. File: ' . $file['name'] . ', Extension: ' . $ext . ', Allowed: ' . implode(', ', $this->allowedExtensions);
            error_log("FileUploader: $msg");
            throw new \Exception($msg);
        }

        // Create subdirectory if specified
        $uploadPath = $this->uploadDir;
        if ($subdir) {
            $uploadPath .= '/' . trim($subdir, '/');
            if (!is_dir($uploadPath)) {
                error_log("FileUploader: Creating directory: $uploadPath");
                if (!mkdir($uploadPath, 0755, true)) {
                    $parentWritable = is_writable(dirname($uploadPath));
                    error_log("FileUploader: Failed to create directory '$uploadPath'. Parent writable: " . ($parentWritable ? 'yes' : 'no') . ". Parent dir: " . dirname($uploadPath));
                    throw new \Exception('Failed to create upload subdirectory: ' . $uploadPath);
                }
                chmod($uploadPath, 0755);
                error_log("FileUploader: Directory created successfully: $uploadPath");
            }
        }

        // Validate temp file exists
        if (!is_uploaded_file($file['tmp_name'])) {
            error_log("FileUploader: Uploaded file is not valid (is_uploaded_file failed): " . $file['tmp_name']);
            throw new \Exception('Invalid uploaded file: ' . $file['tmp_name']);
        }

        // Generate unique filename
        $filename = uniqid('img_') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $filepath = $uploadPath . '/' . $filename;

        // Move uploaded file
        error_log("FileUploader: Moving uploaded file from '{$file['tmp_name']}' to '$filepath'");
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Set proper permissions
            chmod($filepath, 0644);
            
            // Verify file was actually created
            if (!file_exists($filepath)) {
                error_log("FileUploader: File move succeeded but file doesn't exist: $filepath");
                throw new \Exception('File was moved but cannot be verified');
            }
            
            // Log successful upload
            error_log("FileUploader: Successfully uploaded '{$file['name']}' to '$filepath' (Size: {$file['size']} bytes)");
            
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
        return $path && (strpos($path, 'assets/uploads/') === 0 || strpos($path, 'uploads/') === 0);
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
            // Handle old path format: convert uploads/ to assets/uploads/
            if (strpos($imagePath, 'uploads/') === 0 && strpos($imagePath, 'assets/uploads/') !== 0) {
                $imagePath = 'assets/' . $imagePath;
            }
            
            // Ensure it starts with assets/uploads/
            if (strpos($imagePath, 'assets/uploads/') !== 0) {
                // If it has uploads/ but not assets/, add assets prefix
                if (strpos($imagePath, 'uploads/') === 0) {
                    $imagePath = 'assets/' . $imagePath;
                }
            }
            
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
