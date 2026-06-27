<style>
    .forgot-password-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 50%, #06b6d4 100%);
        position: relative;
        overflow: hidden;
        padding: 40px 20px;
    }
    
    .forgot-password-page::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }
    
    .forgot-password-page::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(20px); }
    }
    
    .forgot-password-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
    }
    
    .forgot-password-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100%;
    }
    
    .forgot-password-card .card-body {
        padding: 50px 40px;
    }
    
    .forgot-password-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .forgot-password-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #0369a1 0%, #06b6d4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
    }
    
    .forgot-password-header p {
        color: #666;
        font-size: 1rem;
        margin-bottom: 5px;
    }
    
    .forgot-password-info {
        background-color: #f0f7ff;
        border-left: 4px solid #0369a1;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 25px;
        font-size: 0.9rem;
        color: #555;
    }
    
    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .form-control:focus {
        border-color: #0369a1;
        box-shadow: 0 0 0 0.2rem rgba(3, 105, 161, 0.15);
    }
    
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }
    
    .mb-3 {
        margin-bottom: 1.5rem;
    }
    
    .mb-4 {
        margin-bottom: 2rem;
    }
    
    .btn-send {
        background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 100%);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        width: 100%;
        color: white;
    }
    
    .btn-send:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(3, 105, 161, 0.4);
        color: white;
    }
    
    .back-to-login {
        text-align: center;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid #eee;
    }
    
    .back-to-login a {
        color: #0369a1;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .back-to-login a:hover {
        color: #0c4a6e;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .forgot-password-page {
            padding: 20px 15px;
        }

        .forgot-password-container {
            max-width: 100%;
        }

        .forgot-password-card .card-body {
            padding: 30px 20px;
        }

        .forgot-password-header h1 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="forgot-password-page">
    <div class="forgot-password-container">
        <div class="forgot-password-card">
            <div class="card-body">
                <div class="forgot-password-header">
                    <h1><i class="fas fa-key"></i> Forgot Password</h1>
                    <p>Reset your account password</p>
                </div>

                <?php if (get_flash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= get_flash('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (get_flash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= get_flash('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="forgot-password-info">
                    <strong>📧 How it works:</strong><br>
                    Enter your email address and we'll send you a secure link to reset your password. The link will be valid for 24 hours.
                </div>

                <form method="POST" action="<?= route('auth/forgot-password') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your registered email" value="<?= old('email') ?>" required autofocus>
                        <?php if (get_flash('errors') && isset(get_flash('errors')['email'])): ?>
                            <small class="text-danger d-block mt-1"><?= get_flash('errors')['email'] ?></small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-send">Send Reset Link</button>
                </form>

                <div class="back-to-login">
                    <p>
                        Remember your password? 
                        <a href="<?= route('auth/login') ?>">Back to login</a>
                    </p>
                    <p>
                        Need an account? 
                        <a href="<?= route('auth/register') ?>">Create one</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
