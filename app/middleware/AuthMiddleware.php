<?php
/**
 * Authentication Middleware
 * GINTEC Solutions
 */

namespace App\Middleware;

class AuthMiddleware
{
    public static function check()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . config('url') . '/auth/login');
            exit;
        }
    }

    public static function checkAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . config('url') . '/');
            exit;
        }
    }

    public static function guest()
    {
        if (isset($_SESSION['user'])) {
            header('Location: ' . config('url') . '/dashboard');
            exit;
        }
    }

    public static function verifyToken($token)
    {
        return \Core\Security::verifyCsrfToken($token);
    }
}
