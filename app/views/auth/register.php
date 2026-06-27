
<style>
    .register-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 50%, #06b6d4 100%);
        position: relative;
        overflow: hidden;
        padding: 40px 20px;
    }
    
    .register-page::before {
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
    
    .register-page::after {
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
    
    .register-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .register-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100%;
    }
    
    .register-card .card-body {
        padding: 40px;
    }
    
    .register-header {
        text-align: center;
        margin-bottom: 35px;
    }
    
    .register-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #0369a1 0%, #06b6d4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
    }
    
    .register-header p {
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
    
    .btn-register {
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
    
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(3, 105, 161, 0.4);
        color: white;
    }
    
    .register-divider {
        text-align: center;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid #eee;
    }
    
    .register-footer {
        text-align: center;
    }
    
    .register-footer p {
        color: #666;
        font-size: 0.9rem;
    }
    
    .register-footer a {
        color: #0369a1;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .register-footer a:hover {
        color: #0c4a6e;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .register-page {
            padding: 20px 15px;
        }

        .register-container {
            max-width: 100%;
        }

        .register-card .card-body {
            padding: 25px 20px;
        }

        .register-header h1 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="register-page">
    <div class="register-container">
        <div class="register-card">
            <div class="card-body">
                <div class="register-header">
                    <h1><i class="fas fa-rocket"></i> GINTEC</h1>
                    <p>Create your account to get started</p>
                </div>

                <form method="POST" action="<?= route('auth/register') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= old('first_name') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= old('last_name') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="text-muted d-block mt-1">
                            Minimum 8 characters with uppercase, lowercase, numbers & symbols
                        </small>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirm" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>

                    <button type="submit" class="btn btn-register">Create Account</button>
                </form>

                <div class="register-divider">
                    <div class="register-footer">
                        <p>Already have an account? 
                            <a href="<?= route('auth/login') ?>">Sign in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
