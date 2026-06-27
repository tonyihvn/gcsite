<style>
    .reset-password-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 50%, #06b6d4 100%);
        position: relative;
        overflow: hidden;
        padding: 40px 20px;
    }
    
    .reset-password-page::before {
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
    
    .reset-password-page::after {
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
    
    .reset-password-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
    }
    
    .reset-password-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100%;
    }
    
    .reset-password-card .card-body {
        padding: 50px 40px;
    }
    
    .reset-password-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .reset-password-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #0369a1 0%, #06b6d4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
    }
    
    .reset-password-header p {
        color: #666;
        font-size: 1rem;
    }
    
    .password-strength {
        margin-bottom: 10px;
    }
    
    .strength-meter {
        height: 5px;
        background-color: #e0e0e0;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .strength-meter-fill {
        height: 100%;
        width: 0%;
        transition: width 0.3s ease, background-color 0.3s ease;
        background-color: #dc3545;
    }
    
    .strength-text {
        font-size: 0.85rem;
        margin-top: 5px;
    }
    
    .strength-weak { color: #dc3545; }
    .strength-fair { color: #ffc107; }
    .strength-good { color: #17a2b8; }
    .strength-strong { color: #28a745; }
    
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
    
    .password-requirements {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        font-size: 0.85rem;
    }
    
    .password-requirements li {
        margin-bottom: 5px;
        color: #666;
    }
    
    .password-requirements li.met {
        color: #28a745;
    }
    
    .password-requirements li.met::before {
        content: '✓ ';
        font-weight: bold;
    }
    
    .btn-reset {
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
    
    .btn-reset:hover {
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
        .reset-password-page {
            padding: 20px 15px;
        }

        .reset-password-container {
            max-width: 100%;
        }

        .reset-password-card .card-body {
            padding: 30px 20px;
        }

        .reset-password-header h1 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="reset-password-page">
    <div class="reset-password-container">
        <div class="reset-password-card">
            <div class="card-body">
                <div class="reset-password-header">
                    <h1><i class="fas fa-lock"></i> Reset Password</h1>
                    <p>Create a new secure password</p>
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

                <?php if (get_flash('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach (get_flash('errors') as $field => $message): ?>
                                <li><?= $message ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="password-requirements">
                    <strong>Password must contain:</strong>
                    <ul class="mb-0">
                        <li id="length-check">At least 8 characters</li>
                        <li id="uppercase-check">At least one uppercase letter (A-Z)</li>
                        <li id="lowercase-check">At least one lowercase letter (a-z)</li>
                        <li id="number-check">At least one number (0-9)</li>
                        <li id="special-check">At least one special character (!@#$%^&*)</li>
                    </ul>
                </div>

                <form method="POST" action="<?= route('auth/reset-password') ?>" id="resetForm">
                    <?= csrf_field() ?>
                    
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                    <div class="mb-4">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your new password" required>
                        
                        <div class="password-strength mt-2">
                            <div class="strength-meter">
                                <div class="strength-meter-fill" id="strengthMeterFill"></div>
                            </div>
                            <div class="strength-text">
                                Strength: <span id="strengthText">Weak</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirm" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm your new password" required>
                        <small class="text-muted d-block mt-1">Passwords must match</small>
                    </div>

                    <button type="submit" class="btn btn-reset">Reset Password</button>
                </form>

                <div class="back-to-login">
                    <p>
                        Remember your password? 
                        <a href="<?= route('auth/login') ?>">Back to login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirm');
    const strengthMeterFill = document.getElementById('strengthMeterFill');
    const strengthText = document.getElementById('strengthText');

    const requirements = {
        length: { element: document.getElementById('length-check'), regex: /.{8,}/ },
        uppercase: { element: document.getElementById('uppercase-check'), regex: /[A-Z]/ },
        lowercase: { element: document.getElementById('lowercase-check'), regex: /[a-z]/ },
        number: { element: document.getElementById('number-check'), regex: /[0-9]/ },
        special: { element: document.getElementById('special-check'), regex: /[!@#$%^&*]/ }
    };

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let metRequirements = 0;

        for (const [key, requirement] of Object.entries(requirements)) {
            const isMet = requirement.regex.test(password);
            if (isMet) {
                strength += 20;
                metRequirements++;
                requirement.element.classList.add('met');
            } else {
                requirement.element.classList.remove('met');
            }
        }

        strengthMeterFill.style.width = strength + '%';
        
        if (strength < 40) {
            strengthMeterFill.style.backgroundColor = '#dc3545';
            strengthText.className = 'strength-weak';
            strengthText.textContent = 'Weak';
        } else if (strength < 60) {
            strengthMeterFill.style.backgroundColor = '#ffc107';
            strengthText.className = 'strength-fair';
            strengthText.textContent = 'Fair';
        } else if (strength < 80) {
            strengthMeterFill.style.backgroundColor = '#17a2b8';
            strengthText.className = 'strength-good';
            strengthText.textContent = 'Good';
        } else {
            strengthMeterFill.style.backgroundColor = '#28a745';
            strengthText.className = 'strength-strong';
            strengthText.textContent = 'Strong';
        }
    });

    document.getElementById('resetForm').addEventListener('submit', function(e) {
        if (passwordInput.value !== passwordConfirmInput.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Passwords do not match!',
                confirmButtonColor: '#0369a1'
            });
            passwordConfirmInput.focus();
        }
    });
});
</script>
