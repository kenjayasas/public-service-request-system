<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Public Service Request System') }}</title>
    
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
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --border-dark: #2d3748;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
        }

        /* Navbar Styles */
        .navbar {
            background-color: var(--dark-secondary) !important;
            border-bottom: 1px solid var(--border-dark);
            padding: 1rem 0;
        }

        .navbar-brand {
            color: var(--orange-primary) !important;
            font-weight: 700;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--orange-hover) !important;
            transform: translateY(-2px);
        }

        .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(249, 115, 22, 0.1);
            color: var(--orange-primary) !important;
            transform: translateY(-2px);
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--orange-primary);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(249, 115, 22, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--orange-hover);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(249, 115, 22, 0.3);
        }

        .btn-outline-light {
            background-color: transparent;
            border: 2px solid var(--text-primary);
            color: var(--text-primary);
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background-color: var(--orange-primary);
            border-color: var(--orange-primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(249, 115, 22, 0.3);
        }

        .btn-lg {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-secondary) 100%);
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 50%, rgba(249, 115, 22, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--orange-primary), #ffa726);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .hero-image {
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transition: all 0.5s ease;
        }

        .hero-image:hover {
            transform: scale(1.02) translateY(-10px);
            box-shadow: 0 30px 60px rgba(249, 115, 22, 0.3);
        }

        /* Features Section */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--orange-primary), #ffa726);
            border-radius: 2px;
        }

        .feature-card {
            background-color: var(--dark-card);
            border: 1px solid var(--border-dark);
            border-radius: 16px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--orange-primary), #ffa726);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            border-color: var(--orange-primary);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--orange-primary);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            color: var(--orange-hover);
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .feature-text {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* How It Works Section */
        .how-it-works-section {
            background-color: var(--dark-secondary);
            position: relative;
            overflow: hidden;
        }

        .step-item {
            text-align: center;
            padding: 2rem;
            position: relative;
        }

        .step-number {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--orange-primary), #ffa726);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .step-item:hover .step-number {
            transform: scale(1.1) rotate(360deg);
            box-shadow: 0 15px 30px rgba(249, 115, 22, 0.4);
        }

        .step-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .step-item:hover .step-title {
            color: var(--orange-primary);
        }

        .step-description {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* FAQ Section */
        .faq-section {
            background-color: var(--dark-bg);
        }

        .accordion-item {
            background-color: var(--dark-card);
            border: 1px solid var(--border-dark);
            border-radius: 12px !important;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .accordion-button {
            background-color: var(--dark-card);
            color: var(--text-primary);
            font-weight: 600;
            padding: 1.25rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--dark-card);
            color: var(--orange-primary);
            box-shadow: none;
        }

        .accordion-button:hover {
            background-color: rgba(249, 115, 22, 0.1);
            color: var(--orange-primary);
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: var(--orange-primary);
        }

        .accordion-button::after {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23f97316' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        }

        .accordion-body {
            background-color: var(--dark-secondary);
            color: var(--text-secondary);
            padding: 1.5rem;
            border-top: 1px solid var(--border-dark);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--dark-secondary) 0%, #11151c 100%);
            border-top: 1px solid var(--border-dark);
            padding: 3rem 0;
        }

        .footer-link {
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
            margin-right: 1.5rem;
        }

        .footer-link:hover {
            color: var(--orange-primary);
            transform: translateY(-2px);
        }

        .footer-text {
            color: var(--text-secondary);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease forwards;
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-cog me-2"></i>
                {{ config('app.name', 'PSRS') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <div class="hero-section" style="margin-top: 76px;">
            <div class="container">
                <div class="row align-items-center min-vh-50 py-5">
                    <div class="col-lg-6 animate-fadeInUp">
                        <h1 class="hero-title">
                            Public Service Request System
                        </h1>
                        <p class="hero-subtitle">
                            Easily submit and track your service requests online. 
                            We're here to help you with all your public service needs.
                        </p>
                        <div class="mt-4">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-rocket me-2"></i>Get Started
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                </a>
                            @endguest
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-orange me-2" style="color: var(--orange-primary);"></i>
                                    <span class="text-secondary">500+ Requests</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-orange me-2" style="color: var(--orange-primary);"></i>
                                    <span class="text-secondary">1000+ Users</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-orange me-2" style="color: var(--orange-primary);"></i>
                                    <span class="text-secondary">4.8 Rating</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 animate-fadeInUp delay-1">
                        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                             alt="Public Service" class="img-fluid hero-image">
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-5">
            <div class="container">
                <h2 class="section-title text-center">Key Features</h2>
                <p class="text-center text-secondary mb-5">Everything you need to manage public service requests efficiently</p>
                
                <div class="row g-4">
                    <div class="col-md-4 animate-fadeInUp">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h3 class="feature-title">Easy Request Submission</h3>
                            <p class="feature-text">Submit service requests quickly with our simple form. Add images and location details for better assistance.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 animate-fadeInUp delay-1">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="feature-title">Real-time Tracking</h3>
                            <p class="feature-text">Track your request status in real-time from submission to completion with instant updates.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 animate-fadeInUp delay-2">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3 class="feature-title">Direct Communication</h3>
                            <p class="feature-text">Chat directly with staff members about your requests through our integrated messaging system.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="how-it-works-section py-5">
            <div class="container">
                <h2 class="section-title text-center">How It Works</h2>
                <p class="text-center text-secondary mb-5">Four simple steps to get your issues resolved</p>
                
                <div class="row">
                    <div class="col-lg-3 col-md-6 animate-fadeInUp">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <h4 class="step-title">Register Account</h4>
                            <p class="step-description">Create your account as a citizen to start submitting requests.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 animate-fadeInUp delay-1">
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <h4 class="step-title">Submit Request</h4>
                            <p class="step-description">Fill out the request form with details about your issue.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 animate-fadeInUp delay-2">
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <h4 class="step-title">Track Progress</h4>
                            <p class="step-description">Monitor your request status and communicate with staff.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 animate-fadeInUp delay-3">
                        <div class="step-item">
                            <div class="step-number">4</div>
                            <h4 class="step-title">Provide Feedback</h4>
                            <p class="step-description">Rate completed requests and help us improve our service.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Preview Section -->
        <div class="faq-section py-5">
            <div class="container">
                <h2 class="section-title text-center">Frequently Asked Questions</h2>
                <p class="text-center text-secondary mb-5">Find answers to common questions about our services</p>
                
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item animate-fadeInUp">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        <i class="fas fa-question-circle me-3" style="color: var(--orange-primary);"></i>
                                        How do I submit a service request?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        After logging in, click on "New Request" from your dashboard. Fill in the required details including title, description, category, and location. You can also upload an image if needed. Your request will be automatically assigned to the appropriate department.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item animate-fadeInUp delay-1">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        <i class="fas fa-chart-line me-3" style="color: var(--orange-primary);"></i>
                                        How can I track my request status?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can track all your requests from your personalized dashboard. Each request shows its current status (Pending, In Progress, Completed, or Rejected). You'll also receive email notifications and real-time updates through our messaging system when the status changes.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item animate-fadeInUp delay-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        <i class="fas fa-clock me-3" style="color: var(--orange-primary);"></i>
                                        How long does processing take?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Processing time varies depending on the type of request and current workload. Emergency requests are prioritized. You can check the estimated completion time on your request details page. Our staff works diligently to resolve all requests as quickly as possible.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-5">
                            <a href="{{ route('faqs.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-question-circle me-2"></i>View All FAQs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-cog me-2" style="color: var(--orange-primary);"></i>
                        {{ config('app.name') }}
                    </h5>
                    <p class="footer-text">Making public service requests easier, faster, and more transparent for everyone.</p>
                    <div class="social-links">
                        <a href="#" class="text-secondary me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-secondary me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-secondary me-3"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h6 class="text-white mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link">About Us</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Contact</a></li>
                        <li class="mb-2"><a href="{{ route('faqs.index') }}" class="footer-link">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Support</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">Services</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link">Road Maintenance</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Waste Management</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Water Supply</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Street Lighting</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">Contact Info</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2 footer-text">
                            <i class="fas fa-map-marker-alt me-2" style="color: var(--orange-primary);"></i>
                            City Hall, Main Street
                        </li>
                        <li class="mb-2 footer-text">
                            <i class="fas fa-phone me-2" style="color: var(--orange-primary);"></i>
                            +1 (555) 123-4567
                        </li>
                        <li class="mb-2 footer-text">
                            <i class="fas fa-envelope me-2" style="color: var(--orange-primary);"></i>
                            support@psrs.com
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-secondary">
            
            <div class="row">
                <div class="col-md-6">
                    <p class="footer-text small mb-0">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="footer-link small me-4">Privacy Policy</a>
                    <a href="#" class="footer-link small">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Additional hover effects */
        .social-links a {
            display: inline-block;
            width: 36px;
            height: 36px;
            background-color: var(--dark-card);
            border-radius: 50%;
            text-align: center;
            line-height: 36px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--orange-primary);
            color: white !important;
            transform: translateY(-3px);
        }
        
        .list-unstyled li a {
            position: relative;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .list-unstyled li a::before {
            content: '→';
            position: absolute;
            left: -20px;
            opacity: 0;
            color: var(--orange-primary);
            transition: all 0.3s ease;
        }
        
        .list-unstyled li a:hover {
            padding-left: 5px;
            color: var(--orange-primary) !important;
        }
        
        .list-unstyled li a:hover::before {
            opacity: 1;
            left: -15px;
        }
        
        /* Scroll to top button */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--orange-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .scroll-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-top:hover {
            background-color: var(--orange-hover);
            transform: translateY(-5px);
        }
        
        /* Text colors */
        .text-orange {
            color: var(--orange-primary);
        }
    </style>
    
    <!-- Scroll to top button -->
    <div class="scroll-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <script>
        // Scroll to top button visibility
        window.addEventListener('scroll', function() {
            const scrollTop = document.querySelector('.scroll-top');
            if (window.pageYOffset > 300) {
                scrollTop.classList.add('show');
            } else {
                scrollTop.classList.remove('show');
            }
        });
        
        // Add animation on scroll
        const animateOnScroll = function() {
            const elements = document.querySelectorAll('.feature-card, .step-item, .accordion-item');
            
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementBottom = element.getBoundingClientRect().bottom;
                
                if (elementTop < window.innerHeight && elementBottom > 0) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };
        
        window.addEventListener('scroll', animateOnScroll);
        window.addEventListener('load', animateOnScroll);
    </script>
</body>
</html>