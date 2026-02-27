<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Public Service Request System') }} - Register</title>
    
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
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --border-dark: #2d3748;
            --input-bg: #2d3748;
            --input-border: #4a5568;
            --input-text: #ffffff;
            --input-placeholder: #9ca3af;
            --error-bg: rgba(239, 68, 68, 0.1);
            --error-text: #f87171;
            --success-bg: rgba(34, 197, 94, 0.1);
            --success-text: #4ade80;
            --password-weak: #f87171;
            --password-medium: #fbbf24;
            --password-strong: #4ade80;
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
            background: radial-gradient(circle at 30% 40%, rgba(249, 115, 22, 0.15) 0%, transparent 40%),
                        radial-gradient(circle at 70% 60%, rgba(249, 115, 22, 0.1) 0%, transparent 50%);
            pointer-events: none;
            animation: pulse 8s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
        }

        .register-container {
            max-width: 500px;
            width: 100%;
            margin: 2rem auto;
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
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--orange-primary), #ffa726);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand p {
            color: #d1d5db !important;
            font-size: 1rem;
        }

        /* Register Card */
        .register-card {
            background-color: var(--dark-card);
            border: 1px solid var(--border-dark);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .register-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(249, 115, 22, 0.2);
            border-color: var(--orange-primary);
        }

        /* Progress Bar */
        .registration-progress {
            margin-bottom: 2rem;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 1rem;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--border-dark);
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            width: 35px;
            height: 35px;
            background-color: var(--dark-card);
            border: 2px solid var(--border-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #d1d5db !important;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .step.active {
            background-color: var(--orange-primary);
            border-color: var(--orange-primary);
            color: white !important;
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(249, 115, 22, 0.5);
        }

        .step.completed {
            background-color: #10b981;
            border-color: #10b981;
            color: white !important;
        }

        .step-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
        }

        .step-label {
            color: #9ca3af !important;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .step-label.active {
            color: var(--orange-primary) !important;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .form-group.hidden {
            display: none;
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

        .form-control.is-invalid {
            border-color: #ef4444 !important;
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

        /* Password Strength Meter */
        .password-strength {
            margin-top: 0.75rem;
        }

        .strength-bar {
            display: flex;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .strength-segment {
            height: 4px;
            flex: 1;
            background-color: var(--border-dark);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-segment.weak {
            background-color: #f87171;
        }

        .strength-segment.medium {
            background-color: #fbbf24;
        }

        .strength-segment.strong {
            background-color: #4ade80;
        }

        .strength-text {
            color: #d1d5db !important;
            font-size: 0.85rem;
        }

        .strength-text span {
            color: var(--orange-primary) !important;
            font-weight: 600;
        }

        /* Password Requirements */
        .password-requirements {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 0.75rem;
            border: 1px solid var(--border-dark);
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #d1d5db !important;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .requirement:last-child {
            margin-bottom: 0;
        }

        .requirement i {
            width: 16px;
            font-size: 0.9rem;
            color: #9ca3af;
        }

        .requirement.met {
            color: #4ade80 !important;
        }

        .requirement.met i {
            color: #4ade80 !important;
        }

        /* Error Messages */
        .error-message {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
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
            color: #f87171;
        }

        /* Navigation Buttons */
        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            gap: 1rem;
        }

        .btn-prev, .btn-next, .btn-submit {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-prev {
            background-color: transparent;
            border: 2px solid #4a5568;
            color: #d1d5db !important;
        }

        .btn-prev:hover:not(:disabled) {
            border-color: var(--orange-primary);
            color: var(--orange-primary) !important;
            transform: translateX(-5px);
        }

        .btn-prev:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-next {
            background: linear-gradient(135deg, var(--orange-primary), #ffa726);
            color: white !important;
            box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
            margin-left: auto;
        }

        .btn-next:hover:not(:disabled) {
            transform: translateX(5px);
            box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--orange-primary), #ffa726);
            color: white !important;
            box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
            width: 100%;
            justify-content: center;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-submit i {
            transition: all 0.3s ease;
        }

        .btn-submit:hover:not(:disabled) i {
            transform: translateX(5px);
        }

        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-dark);
        }

        .login-link p {
            color: #d1d5db !important;
            margin-bottom: 0.5rem;
        }

        .btn-login {
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
            border: 1px solid rgba(249, 115, 22, 0.2);
        }

        .btn-login:hover {
            background-color: var(--orange-primary);
            color: white !important;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
            border-color: var(--orange-primary);
        }

        .btn-login:hover i {
            transform: translateX(-5px);
        }

        .btn-login i {
            transition: all 0.3s ease;
            color: var(--orange-primary);
        }

        .btn-login:hover i {
            color: white;
        }

        /* Footer */
        .register-footer {
            text-align: center;
            margin-top: 2rem;
            color: #9ca3af !important;
            font-size: 0.875rem;
        }

        .register-footer a {
            color: var(--orange-primary) !important;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .register-footer a:hover {
            color: #fbbf24 !important;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .register-container {
                padding: 1rem;
            }
            
            .register-card {
                padding: 1.5rem;
            }
            
            .brand h1 {
                font-size: 1.8rem;
            }
            
            .brand-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
            
            .step-labels {
                display: none;
            }
        }

        /* Success Message */
        .success-message {
            text-align: center;
            padding: 2rem;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background-color: rgba(16, 185, 129, 0.1);
            border: 3px solid #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            color: #10b981;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        /* Tooltip */
        .tooltip-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            background-color: #9ca3af;
            border-radius: 50%;
            color: #0a0c0f;
            font-size: 0.75rem;
            cursor: help;
            margin-left: 0.5rem;
            transition: all 0.3s ease;
        }

        .tooltip-icon:hover {
            background-color: var(--orange-primary);
            color: white;
        }

        /* Text visibility global fixes */
        .text-secondary {
            color: #d1d5db !important;
        }

        .text-primary {
            color: #ffffff !important;
        }

        .text-muted {
            color: #9ca3af !important;
        }

        /* Ensure all text has proper contrast */
        h1, h2, h3, h4, h5, h6, p, span, a, label, input, button, div {
            color: #ffffff;
        }

        .text-secondary, .text-muted, .step-label, .strength-text, .requirement, .register-footer {
            color: #d1d5db !important;
        }

        .text-muted, .step:not(.active):not(.completed) {
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
    <div class="register-container">
        <!-- Brand Section -->
        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Create Account</h1>
            <p>Join our community to access public services</p>
        </div>

        <!-- Register Card -->
        <div class="register-card">
            <!-- Registration Progress -->
            <div class="registration-progress">
                <div class="progress-steps">
                    <div class="step active" id="step1">1</div>
                    <div class="step" id="step2">2</div>
                    <div class="step" id="step3">3</div>
                </div>
                <div class="step-labels">
                    <span class="step-label active" id="label1">Account</span>
                    <span class="step-label" id="label2">Profile</span>
                    <span class="step-label" id="label3">Security</span>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Step 1: Account Information -->
                <div id="step1-content" class="form-group">
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            Full Name
                            <span class="tooltip-icon" title="Enter your full legal name">?</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input 
                                id="name" 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus 
                                autocomplete="name"
                                placeholder="John Doe"
                            >
                        </div>
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

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
                                autocomplete="username"
                                placeholder="you@example.com"
                            >
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Step 2: Profile Information (Optional) -->
                <div id="step2-content" class="form-group hidden">
                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone"></i>
                            Phone Number (Optional)
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone input-icon"></i>
                            <input 
                                id="phone" 
                                type="text" 
                                class="form-control" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                placeholder="+1 (555) 123-4567"
                            >
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Address (Optional)
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                            <textarea 
                                id="address" 
                                class="form-control" 
                                name="address" 
                                rows="3"
                                placeholder="123 Main St, City, State 12345"
                            >{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Security -->
                <div id="step3-content" class="form-group hidden">
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
                                autocomplete="new-password"
                                placeholder="Create a strong password"
                                onkeyup="checkPasswordStrength()"
                            >
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                        </div>

                        <!-- Password Strength Meter -->
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-segment" id="strength1"></div>
                                <div class="strength-segment" id="strength2"></div>
                                <div class="strength-segment" id="strength3"></div>
                            </div>
                            <div class="strength-text">
                                Password strength: <span id="strength-text">Enter password</span>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="password-requirements">
                            <div class="requirement" id="req-length">
                                <i class="fas fa-circle"></i>
                                At least 8 characters
                            </div>
                            <div class="requirement" id="req-uppercase">
                                <i class="fas fa-circle"></i>
                                One uppercase letter
                            </div>
                            <div class="requirement" id="req-lowercase">
                                <i class="fas fa-circle"></i>
                                One lowercase letter
                            </div>
                            <div class="requirement" id="req-number">
                                <i class="fas fa-circle"></i>
                                One number
                            </div>
                            <div class="requirement" id="req-special">
                                <i class="fas fa-circle"></i>
                                One special character
                            </div>
                        </div>

                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock"></i>
                            Confirm Password
                        </label>
                        <div class="password-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                class="form-control @error('password_confirmation') is-invalid @enderror" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Confirm your password"
                                onkeyup="checkPasswordMatch()"
                            >
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation', this)"></i>
                        </div>
                        <div id="password-match-message" class="requirement" style="margin-top: 0.5rem;">
                            <i class="fas fa-circle"></i>
                            Passwords match
                        </div>
                        @error('password_confirmation')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="form-navigation">
                    <button type="button" class="btn-prev" id="prevBtn" onclick="prevStep()" disabled>
                        <i class="fas fa-arrow-left"></i>
                        Previous
                    </button>
                    
                    <button type="button" class="btn-next" id="nextBtn" onclick="nextStep()">
                        Next
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    
                    <button type="submit" class="btn-submit hidden" id="submitBtn">
                        <span>Create Account</span>
                        <i class="fas fa-user-plus"></i>
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <p>Already have an account?</p>
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="fas fa-arrow-left"></i>
                    Sign in to your account
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="register-footer">
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
        let currentStep = 1;
        const totalSteps = 3;

        // Initialize form
        function initForm() {
            updateSteps();
        }

        // Update step indicators
        function updateSteps() {
            // Update step circles
            for (let i = 1; i <= totalSteps; i++) {
                const step = document.getElementById(`step${i}`);
                const label = document.getElementById(`label${i}`);
                const content = document.getElementById(`step${i}-content`);
                
                if (i < currentStep) {
                    step.classList.add('completed');
                    step.classList.remove('active');
                    step.innerHTML = '<i class="fas fa-check"></i>';
                } else if (i === currentStep) {
                    step.classList.add('active');
                    step.classList.remove('completed');
                    step.innerHTML = i;
                } else {
                    step.classList.remove('active', 'completed');
                    step.innerHTML = i;
                }
                
                // Update labels
                if (i === currentStep) {
                    label?.classList.add('active');
                } else {
                    label?.classList.remove('active');
                }
                
                // Show/hide content
                if (content) {
                    if (i === currentStep) {
                        content.classList.remove('hidden');
                    } else {
                        content.classList.add('hidden');
                    }
                }
            }

            // Update navigation buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            prevBtn.disabled = currentStep === 1;

            if (currentStep === totalSteps) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        // Next step
        function nextStep() {
            if (currentStep < totalSteps) {
                // Validate current step
                if (validateStep(currentStep)) {
                    currentStep++;
                    updateSteps();
                }
            }
        }

        // Previous step
        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                updateSteps();
            }
        }

        // Validate step
        function validateStep(step) {
            if (step === 1) {
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                
                if (!name || !email) {
                    alert('Please fill in all required fields');
                    return false;
                }
                
                if (!email.includes('@')) {
                    alert('Please enter a valid email address');
                    return false;
                }
            }
            
            if (step === 3) {
                const password = document.getElementById('password').value;
                const confirm = document.getElementById('password_confirmation').value;
                
                if (!password || !confirm) {
                    alert('Please fill in all password fields');
                    return false;
                }
                
                if (password !== confirm) {
                    alert('Passwords do not match');
                    return false;
                }
            }
            
            return true;
        }

        // Password strength checker
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strength1 = document.getElementById('strength1');
            const strength2 = document.getElementById('strength2');
            const strength3 = document.getElementById('strength3');
            const strengthText = document.getElementById('strength-text');

            // Requirements
            const hasLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            // Update requirement indicators
            updateRequirement('req-length', hasLength);
            updateRequirement('req-uppercase', hasUpperCase);
            updateRequirement('req-lowercase', hasLowerCase);
            updateRequirement('req-number', hasNumber);
            updateRequirement('req-special', hasSpecial);

            // Calculate strength
            const requirements = [hasLength, hasUpperCase, hasLowerCase, hasNumber, hasSpecial];
            const metCount = requirements.filter(Boolean).length;

            // Update strength meter
            strength1.className = 'strength-segment';
            strength2.className = 'strength-segment';
            strength3.className = 'strength-segment';

            if (password.length === 0) {
                strengthText.textContent = 'Enter password';
            } else if (metCount <= 2) {
                strength1.classList.add('weak');
                strengthText.textContent = 'Weak';
            } else if (metCount <= 4) {
                strength1.classList.add('medium');
                strength2.classList.add('medium');
                strengthText.textContent = 'Medium';
            } else {
                strength1.classList.add('strong');
                strength2.classList.add('strong');
                strength3.classList.add('strong');
                strengthText.textContent = 'Strong';
            }

            checkPasswordMatch();
        }

        // Update requirement indicator
        function updateRequirement(elementId, met) {
            const element = document.getElementById(elementId);
            if (met) {
                element.classList.add('met');
                element.querySelector('i').className = 'fas fa-check-circle';
            } else {
                element.classList.remove('met');
                element.querySelector('i').className = 'fas fa-circle';
            }
        }

        // Check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const matchMessage = document.getElementById('password-match-message');

            if (confirm.length > 0) {
                if (password === confirm) {
                    matchMessage.classList.add('met');
                    matchMessage.querySelector('i').className = 'fas fa-check-circle';
                } else {
                    matchMessage.classList.remove('met');
                    matchMessage.querySelector('i').className = 'fas fa-circle';
                }
            } else {
                matchMessage.classList.remove('met');
                matchMessage.querySelector('i').className = 'fas fa-circle';
            }
        }

        // Toggle password visibility
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Add floating animation to inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.color = 'var(--orange-primary)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#9ca3af';
            });
        });

        // Loading state on form submission
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
            submitBtn.disabled = true;
        });

        // Initialize on page load
        initForm();
    </script>

    <style>
        /* Additional styles for hidden class */
        .hidden {
            display: none !important;
        }
        
        /* Password toggle icon positioning */
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

        /* Textarea styling */
        textarea.form-control {
            min-height: 80px;
            resize: vertical;
        }

        /* Loading spinner */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Success animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-card {
            animation: slideIn 0.5s ease;
        }
    </style>
</body>
</html>