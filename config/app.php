<?php
/**
 * Application Configuration
 * GINTEC Solutions Consults Ltd
 */

require_once __DIR__ . '/../core/DotEnv.php';

$env = new \Core\DotEnv(__DIR__ . '/../.env');
$env->load();

return [
    'name' => $_ENV['APP_NAME'] ?? 'GINTEC Solutions Consults Ltd',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost/gintec',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => (bool) ($_ENV['APP_DEBUG'] ?? false),
    
    'key' => $_ENV['APP_KEY'] ?? 'gintec-secret-key',
    'encryption_key' => $_ENV['ENCRYPTION_KEY'] ?? 'gintec-encryption-key',
    
    'timezone' => 'Africa/Lagos',
    'locale' => 'en_NG',
    
    'company' => [
        'name' => $_ENV['COMPANY_NAME'] ?? 'GINTEC Solutions Consults Ltd',
        'address' => $_ENV['COMPANY_ADDRESS'] ?? '2nd Floor, Peace Plaza B, Utako, Abuja',
        'phone' => $_ENV['COMPANY_PHONE'] ?? '07067973091',
        'email' => $_ENV['COMPANY_EMAIL'] ?? 'info@gintec.com.ng',
        'website' => $_ENV['COMPANY_WEBSITE'] ?? 'www.gintec.com.ng',
        'ceo' => $_ENV['CEO_NAME'] ?? 'Anthony Nwokoma',
    ],
    
    'session' => [
        'timeout' => $_ENV['SESSION_TIMEOUT'] ?? 3600,
        'remember_duration' => $_ENV['REMEMBER_ME_DURATION'] ?? 2592000,
    ],
    
    'upload' => [
        'max_size' => $_ENV['MAX_UPLOAD_SIZE'] ?? 10485760,
        'allowed_extensions' => explode(',', $_ENV['ALLOWED_EXTENSIONS'] ?? 'jpg,jpeg,png,gif,pdf'),
        'directory' => __DIR__ . '/../public/assets/uploads/',
    ],
];
