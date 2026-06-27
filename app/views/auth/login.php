
<style>
    .login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 50%, #06b6d4 100%);
        position: relative;
        overflow: hidden;
        padding: 40px 20px;
    }
    
    .login-page::before {
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
    
    .login-page::after {
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
    
    .login-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
    }
    
    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100%;
    }
    
    .login-card .card-body {
        padding: 50px 40px;
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .login-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #0369a1 0%, #06b6d4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
    }
    
    .login-header p {
        color: #666;
        font-size: 1rem;
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
    
    .btn-login {
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
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(3, 105, 161, 0.4);
        color: white;
    }
    
    .login-divider {
        text-align: center;
        position: relative;
        margin: 30px 0;
        color: #999;
    }
    
    .login-divider::before,
    .login-divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 45%;
        height: 1px;
        background: #ddd;
    }
    
    .login-divider::before { left: 0; }
    .login-divider::after { right: 0; }
    
    .login-footer {
        text-align: center;
        margin-top: 30px;
    }
    
    .login-footer p {
        margin: 8px 0;
        color: #666;
        font-size: 0.9rem;
    }
    
    .login-footer a {
        color: #0369a1;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .login-footer a:hover {
        color: #0c4a6e;
        text-decoration: underline;
    }
    
    .form-check-label {
        color: #666;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .login-page {
            padding: 20px 15px;
        }

        .login-container {
            max-width: 100%;
        }

        .login-card .card-body {
            padding: 30px 20px;
        }

        .login-header h1 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="card-body">
                <div class="login-header">
                    <h1><i class="fas fa-rocket"></i> GINTEC</h1>
                    <p>Welcome back to your account</p>
                </div>

                <form method="POST" action="<?= route('auth/login') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                        <label class="form-check-label" for="remember_me">
                            Remember me for 30 days
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">Sign In</button>
                </form>

                <div class="login-divider">or</div>

                <div class="login-footer">
                    <p>Don't have an account? 
                        <a href="<?= route('auth/register') ?>">Create account</a>
                    </p>
                    <p>
                        <a href="<?= route('auth/forgot-password') ?>">Forgot password?</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
