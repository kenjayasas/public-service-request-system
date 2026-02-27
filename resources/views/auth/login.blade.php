<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Public Service Request System') }} - Login</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --dark-bg: #0a0c0f;
            --dark-secondary: #1a1e24;
            --dark-card: #1e2329;
            --orange-primary: #f97316;
            --orange-hover: #fb923c;
            --orange-light: #ffedd5;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            --border-dark: #2d3748;
            --input-bg: #2d3748;
            --input-border: #4a5568;
            --input-text: #ffffff;
            --input-placeholder: #9ca3af;
            --error-bg: rgba(239, 68, 68, 0.1);
            --error-text: #f87171;
            --success-bg: rgba(34, 197, 94, 0.1);
            --success-text: #4ade80;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-secondary) 100%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(249, 115, 22, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(249, 115, 22, 0.1) 0%, transparent 50%);
            pointer-events: none;
            animation: pulse 10s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Logo/Brand */
        .brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--orange-primary), #ffa726);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .brand h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--orange-primary), #ffa726);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* Login Card */
        .login-card {
            background-color: var(--dark-card);
            border: 1px solid var(--border-dark);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(249, 115, 22, 0.2);
            border-color: var(--orange-primary);
        }

        /* Session Status */
        .session-status {
            background-color: var(--success-bg);
            border: 1px solid var(--success-text);
            color: var(--success-text);
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideDown 0.5s ease;
        }

        .session-status i {
            font-size: 1.25rem;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: #ffffff !important;
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: var(--orange-primary);
            font-size: 1rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            background-color: #2d3748 !important;
            border: 2px solid #4a5568 !important;
            border-radius: 12px;
            color: #ffffff !important;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--orange-primary) !important;
            background-color: #1e2329 !important;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
        }

        .form-control:focus + .input-icon {
            color: var(--orange-primary);
        }

        .form-control::placeholder {
            color: #9ca3af !important;
            opacity: 1;
        }

        /* Password field specific */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .password-toggle:hover {
            color: var(--orange-primary);
        }

        /* Error Messages */
        .error-message {
            background-color: var(--error-bg);
            border: 1px solid var(--error-text);
            color: #f87171 !important;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .error-message i {
            font-size: 1rem;
        }

        /* Remember Me Checkbox */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 1.25rem;
            height: 1.25rem;
            background-color: #2d3748;
            border: 2px solid #4a5568;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox-wrapper input[type="checkbox"]:checked {
            background-color: var(--orange-primary);
            border-color: var(--orange-primary);
        }

        .checkbox-wrapper input[type="checkbox"]:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.3);
        }

        .checkbox-wrapper span {
            color: #9ca3af !important;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .checkbox-wrapper:hover span {
            color: var(--orange-primary) !important;
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--orange-primary), #ffa726);
            border: none;
            border-radius: 12px;
            color: white !important;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login i {
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover i {
            transform: translateX(5px);
        }

        /* Forgot Password Link */
        .forgot-password {
            color: #9ca3af !important;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .forgot-password:hover {
            color: var(--orange-primary) !important;
            transform: translateX(-5px);
        }

        .forgot-password i {
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .forgot-password:hover i {
            transform: translateX(-3px);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-dark);
        }

        .register-link p {
            color: #9ca3af !important;
            margin-bottom: 0.5rem;
        }

        .btn-register {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--orange-primary) !important;
            text-decoration: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            background-color: rgba(249, 115, 22, 0.1);
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background-color: var(--orange-primary);
            color: white !important;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
        }

        .btn-register:hover i {
            transform: translateX(5px);
        }

        .btn-register i {
            transition: all 0.3s ease;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: #6b7280 !important;
            font-size: 0.875rem;
        }

        .login-footer a {
            color: var(--orange-primary) !important;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-footer a:hover {
            color: var(--orange-hover) !important;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-card {
                padding: 1.5rem;
            }
            
            .brand h1 {
                font-size: 1.5rem;
            }
            
            .brand-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
        }

        /* Text visibility global fixes */
        .text-secondary {
            color: #9ca3af !important;
        }

        .text-primary {
            color: #ffffff !important;
        }

        .text-muted {
            color: #6b7280 !important;
        }

        /* Ensure all text has proper contrast */
        h1, h2, h3, h4, h5, h6, p, span, a, label, input, button {
            color: #ffffff;
        }

        .text-secondary, .text-muted {
            color: #9ca3af !important;
        }

        /* Fix for any Bootstrap overrides */
        .text-dark {
            color: #ffffff !important;
        }

        .text-body {
            color: #ffffff !important;
        }

        /* Loading spinner color */
        .fa-spinner {
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Brand Section -->
        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-cog"></i>
            </div>
            <h1>Welcome Back!</h1>
            <p>Sign in to access your account</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <!-- Session Status -->
            @if (session('status'))
                <div class="session-status">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            id="email" 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="Enter your email"
                        >
                    </div>
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <div class="password-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            id="password" 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        >
                        <i class="fas fa-eye password-toggle" onclick="togglePassword()" id="togglePassword"></i>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="remember" id="remember_me">
                        <span>
                            <i class="fas fa-check-circle me-1"></i>
                            Remember me
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            <i class="fas fa-arrow-left"></i>
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    <span>Sign In</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <!-- Register Link -->
            <div class="register-link">
                <p>Don't have an account?</p>
                <a href="{{ route('register') }}" class="btn-register">
                    <i class="fas fa-user-plus"></i>
                    Create an account
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="#">Privacy Policy</a> • 
                <a href="#">Terms of Service</a> • 
                <a href="{{ route('faqs.index') }}">FAQ</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password visibility toggle
        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');
            
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add floating animation to form inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.color = 'var(--orange-primary)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#9ca3af';
            });
        });

        // Auto-dismiss session status after 5 seconds
        const sessionStatus = document.querySelector('.session-status');
        if (sessionStatus) {
            setTimeout(() => {
                sessionStatus.style.animation = 'slideUp 0.5s ease forwards';
                setTimeout(() => {
                    sessionStatus.remove();
                }, 500);
            }, 5000);
        }

        // Add slideUp animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideUp {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(-20px);
                }
            }
        `;
        document.head.appendChild(style);

        // Loading state for form submission
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('.btn-login');
        
        form.addEventListener('submit', function(e) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
            submitBtn.disabled = true;
        });

        // Add input validation styling
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('invalid', function(e) {
                e.preventDefault();
                this.classList.add('is-invalid');
            });
        });
    </script>
</body>
</html>