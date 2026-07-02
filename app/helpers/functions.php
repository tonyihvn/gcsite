<?php
/**
 * Application Helper Functions
 * GINTEC Solutions
 */

function config($key = null)
{
    static $config = null;
    if ($config === null) {
        $config = require dirname(dirname(dirname(__FILE__))) . '/config/app.php';
    }
    
    if ($key === null) {
        return $config;
    }
    
    return $config[$key] ?? null;
}

function old($key, $default = '')
{
    return $_POST[$key] ?? $default;
}

function csrf_token()
{
    return \Core\Security::generateCsrfToken();
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function auth()
{
    return $_SESSION['user'] ?? null;
}

function auth_id()
{
    return $_SESSION['user']['id'] ?? null;
}

function is_auth()
{
    return isset($_SESSION['user']);
}

function redirect($path)
{
    header('Location: ' . config('url') . '/' . ltrim($path, '/'));
    exit;
}

function back()
{
    redirect($_SERVER['HTTP_REFERER'] ?? '/');
}

function route($path)
{
    return config('url') . '/' . ltrim($path, '/');
}

function asset($path)
{
    return config('url') . '/assets/' . ltrim($path, '/');
}

function upload_path($path)
{
    return 'uploads/' . ltrim($path, '/');
}

function flash($key = null)
{
    if ($key === null) {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }
    
    return $_SESSION['flash'][$key] ?? null;
}

function get_flash($key)
{
    return $_SESSION['flash'][$key] ?? null;
}

function set_flash($key, $value)
{
    $_SESSION['flash'][$key] = $value;
}

function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit;
}

function dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function is_admin()
{
    return isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function is_user()
{
    return isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'user';
}

function abort($code, $message = '')
{
    http_response_code($code);
    die($message ?: "Error $code");
}

function view($view, $data = [])
{
    extract($data);
    $viewPath = __DIR__ . '/../app/views/' . str_replace('.', '/', $view) . '.php';
    
    if (!file_exists($viewPath)) {
        abort(404, "View not found: $viewPath");
    }
    
    include $viewPath;
}
