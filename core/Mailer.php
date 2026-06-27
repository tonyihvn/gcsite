<?php
/**
 * Email Mailer Class
 * Handles SMTP email sending for GINTEC Solutions
 */

namespace Core;

class Mailer
{
    private $host;
    private $port;
    private $encryption;
    private $username;
    private $password;
    private $fromEmail;
    private $fromName;

    public function __construct()
    {
        $this->host = $_ENV['MAIL_HOST'] ?? 'smtp.mailtrap.io';
        $this->port = $_ENV['MAIL_PORT'] ?? 2525;
        $this->encryption = $_ENV['MAIL_ENCRYPTION'] ?? 'tls';
        $this->username = $_ENV['MAIL_USER'] ?? '';
        $this->password = $_ENV['MAIL_PASS'] ?? '';
        $this->fromEmail = $_ENV['MAIL_FROM'] ?? 'noreply@gintec.com.ng';
        $this->fromName = $_ENV['MAIL_FROM_NAME'] ?? 'GINTEC Solutions';
    }

    /**
     * Send email using PHP's mail() function with SMTP headers
     */
    public function send($to, $subject, $body, $isHtml = true)
    {
        // For development, use PHP mail() with proper headers
        // In production, consider using PHPMailer or SwiftMailer
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: " . ($isHtml ? "text/html; charset=UTF-8" : "text/plain; charset=UTF-8") . "\r\n";
        $headers .= "From: {$this->fromName} <{$this->fromEmail}>\r\n";
        $headers .= "Reply-To: {$this->fromEmail}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        return mail($to, $subject, $body, $headers);
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($email, $userName, $resetLink)
    {
        $subject = "Password Reset Request - GINTEC Solutions";
        
        $body = "
        <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
                    .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    .header { background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 100%); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; margin: -30px -30px 30px -30px; }
                    .header h1 { margin: 0; font-size: 24px; }
                    .content { color: #333; line-height: 1.6; }
                    .button { display: inline-block; background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin-top: 20px; margin-bottom: 20px; }
                    .footer { text-align: center; padding-top: 20px; border-top: 1px solid #eee; color: #999; font-size: 12px; }
                    .warning { background-color: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>🔐 Password Reset Request</h1>
                    </div>
                    <div class='content'>
                        <p>Hello <strong>{$userName}</strong>,</p>
                        
                        <p>We received a request to reset your password for your GINTEC Solutions account. If you didn't make this request, you can safely ignore this email.</p>
                        
                        <div class='warning'>
                            <strong>⏰ This reset link is valid for 24 hours only.</strong>
                        </div>
                        
                        <p>To reset your password, click the button below:</p>
                        
                        <center>
                            <a href='{$resetLink}' class='button'>Reset My Password</a>
                        </center>
                        
                        <p>Or copy and paste this link in your browser:</p>
                        <p style='word-break: break-all; background-color: #f5f5f5; padding: 10px; border-radius: 4px;'>{$resetLink}</p>
                        
                        <h3>For Your Security:</h3>
                        <ul>
                            <li>Never share your reset link with anyone</li>
                            <li>GINTEC will never ask for your password via email</li>
                            <li>If you didn't request this, please change your password immediately</li>
                        </ul>
                        
                        <p>If you have any issues, please contact our support team at <strong>{$this->fromEmail}</strong></p>
                    </div>
                    <div class='footer'>
                        <p>&copy; 2024 GINTEC Solutions Consults Ltd. All rights reserved.</p>
                        <p>
                            <a href='http://localhost:8001' style='color: #0369a1; text-decoration: none;'>Visit Website</a> | 
                            <a href='http://localhost:8001/contact' style='color: #0369a1; text-decoration: none;'>Contact Support</a>
                        </p>
                    </div>
                </div>
            </body>
        </html>
        ";

        return $this->send($email, $subject, $body, true);
    }

    /**
     * Send welcome email
     */
    public function sendWelcomeEmail($email, $userName)
    {
        $subject = "Welcome to GINTEC Solutions!";
        
        $body = "
        <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
                    .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    .header { background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 100%); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; margin: -30px -30px 30px -30px; }
                    .header h1 { margin: 0; font-size: 24px; }
                    .button { display: inline-block; background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
                    .footer { text-align: center; padding-top: 20px; border-top: 1px solid #eee; color: #999; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>🎉 Welcome to GINTEC Solutions!</h1>
                    </div>
                    <div style='color: #333; line-height: 1.6;'>
                        <p>Hello <strong>{$userName}</strong>,</p>
                        
                        <p>Thank you for registering with GINTEC Solutions! We're excited to have you on board.</p>
                        
                        <p>With your account, you can now:</p>
                        <ul>
                            <li>Browse our premium products and services</li>
                            <li>Manage your subscriptions</li>
                            <li>Track your invoices and payments</li>
                            <li>Access exclusive resources</li>
                        </ul>
                        
                        <p>
                            <a href='http://localhost:8001/dashboard' class='button'>Go to Dashboard</a>
                        </p>
                        
                        <p>If you have any questions or need assistance, our support team is here to help!</p>
                        
                        <p>Best regards,<br><strong>The GINTEC Solutions Team</strong></p>
                    </div>
                    <div class='footer'>
                        <p>&copy; 2024 GINTEC Solutions Consults Ltd. All rights reserved.</p>
                    </div>
                </div>
            </body>
        </html>
        ";

        return $this->send($email, $subject, $body, true);
    }
}
