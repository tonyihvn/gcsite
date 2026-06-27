<?php
/**
 * Authentication Controller
 * GINTEC Solutions
 */

namespace App\Controllers;

use Core\Controller;
use Core\Security;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        \App\Middleware\AuthMiddleware::guest();

        $this->view('auth.login', [
            'page_title' => 'Login - ' . config('company.name'),
        ]);
    }

    public function login()
    {
        \App\Middleware\AuthMiddleware::guest();

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $errors = $this->validate($_POST, $rules);

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('auth/login');
        }

        if (!Security::checkRateLimit($_POST['email'], 5, 300)) {
            set_flash('error', 'Too many login attempts. Please try again later.');
            $this->redirect('auth/login');
        }

        $user = (new User())->first('email', $_POST['email']);

        if (!$user || !Security::verifyPassword($_POST['password'], $user['password'])) {
            set_flash('error', 'Invalid email or password');
            $this->redirect('auth/login');
        }

        if ($user['status'] !== 'active') {
            set_flash('error', 'Your account is not active. Please contact support.');
            $this->redirect('auth/login');
        }

        // Login successful
        $_SESSION['user'] = $user;
        
        // Update last login
        (new User())->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        if (isset($_POST['remember_me'])) {
            $token = Security::generateToken();
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
            (new User())->update($user['id'], ['remember_token' => $token]);
        }

        set_flash('success', 'Welcome back, ' . $user['first_name'] . '!');
        $this->redirect($user['role'] === 'admin' ? 'admin' : 'dashboard');
    }

    public function registerForm()
    {
        \App\Middleware\AuthMiddleware::guest();

        $this->view('auth.register', [
            'page_title' => 'Register - ' . config('company.name'),
        ]);
    }

    public function register()
    {
        \App\Middleware\AuthMiddleware::guest();

        $rules = [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'required|min:8',
            'password_confirm' => 'required',
        ];

        $errors = $this->validate($_POST, $rules);

        if ($_POST['password'] !== $_POST['password_confirm']) {
            $errors['password_confirm'] = 'Passwords do not match';
        }

        if (!Security::isStrongPassword($_POST['password'])) {
            $errors['password'] = 'Password must contain uppercase, lowercase, numbers, and special characters';
        }

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('auth/register');
        }

        // Check if email already exists
        if ((new User())->first('email', $_POST['email'])) {
            set_flash('error', 'Email already registered');
            $this->redirect('auth/register');
        }

        $userId = (new User())->create([
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'password' => $_POST['password'],
            'role' => 'user',
            'status' => 'active',
        ]);

        set_flash('success', 'Registration successful! You can now login.');
        $this->redirect('auth/login');
    }

    public function logout()
    {
        \App\Middleware\AuthMiddleware::check();

        unset($_SESSION['user']);
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');

        set_flash('success', 'You have been logged out successfully');
        $this->redirect('');
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('auth.forgot-password', [
                'page_title' => 'Forgot Password - ' . config('company.name'),
            ]);
            return;
        }

        // Validate email
        $rules = ['email' => 'required|email'];
        $errors = $this->validate($_POST, $rules);

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('auth/forgot-password');
            return;
        }

        $email = $_POST['email'];
        $user = (new User())->first('email', $email);

        // Always show success message for security (don't reveal if email exists)
        set_flash('success', 'If this email is registered with us, you will receive a password reset link via email. Please check your inbox and spam folder.');

        if (!$user) {
            $this->redirect('auth/login');
            return;
        }

        // Generate reset token
        $resetToken = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Save token to database
        (new User())->update($user['id'], [
            'reset_token' => hash('sha256', $resetToken),
            'reset_token_expires_at' => $expiresAt
        ]);

        // Send reset email
        $resetLink = config('app.url') . '/auth/reset-password/' . $resetToken;
        $mailer = new \Core\Mailer();
        $mailer->sendPasswordResetEmail($email, $user['first_name'], $resetLink);

        $this->redirect('auth/login');
    }

    public function resetPasswordForm($token = null)
    {
        if (!$token) {
            set_flash('error', 'Invalid or missing reset token');
            $this->redirect('auth/login');
            return;
        }

        $this->view('auth.reset-password', [
            'token' => $token,
            'page_title' => 'Reset Password - ' . config('company.name'),
        ]);
    }

    public function resetPassword()
    {
        \App\Middleware\AuthMiddleware::guest();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/forgot-password');
            return;
        }

        $rules = [
            'token' => 'required',
            'password' => 'required|min:8',
            'password_confirm' => 'required',
        ];

        $errors = $this->validate($_POST, $rules);

        if ($_POST['password'] !== $_POST['password_confirm']) {
            $errors['password_confirm'] = 'Passwords do not match';
        }

        if (!Security::isStrongPassword($_POST['password'])) {
            $errors['password'] = 'Password must contain uppercase, lowercase, numbers, and special characters';
        }

        if (!empty($errors)) {
            set_flash('errors', $errors);
            $this->redirect('auth/reset-password/' . $_POST['token']);
            return;
        }

        // Verify token
        $hashedToken = hash('sha256', $_POST['token']);
        $user = null;

        // Find user with matching reset token
        $users = (new User())->all();
        foreach ($users as $u) {
            if ($u['reset_token'] === $hashedToken) {
                $user = $u;
                break;
            }
        }

        if (!$user) {
            set_flash('error', 'Invalid reset token');
            $this->redirect('auth/forgot-password');
            return;
        }

        // Check if token is expired
        if (strtotime($user['reset_token_expires_at']) < time()) {
            set_flash('error', 'This password reset link has expired. Please request a new one.');
            $this->redirect('auth/forgot-password');
            return;
        }

        // Update password
        (new User())->update($user['id'], [
            'password' => $_POST['password'],
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);

        set_flash('success', 'Password reset successful! Please login with your new password.');
        $this->redirect('auth/login');
    }
}
