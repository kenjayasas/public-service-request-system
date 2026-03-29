<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Public Service Request System') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

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
            --text-muted: #6b7280;
            --border-dark: #2d3748;
            --input-bg: #2d3748;
            --input-border: #4a5568;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --header-height: 70px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Main Layout with Sidebar */
        #app {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--dark-secondary) 0%, #151a21 100%);
            border-right: 1px solid var(--border-dark);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .sidebar-link span,
        .sidebar.collapsed .sidebar-brand span,
        .sidebar.collapsed .user-info span,
        .sidebar.collapsed .user-role,
        .sidebar.collapsed .badge {
            display: none;
        }

        .sidebar.collapsed .sidebar-link i {
            margin-right: 0;
            font-size: 1.4rem;
        }

        .sidebar.collapsed .sidebar-brand i {
            margin-right: 0;
        }

        .sidebar.collapsed .user-avatar {
            margin-right: 0;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand {
            color: var(--orange-primary) !important;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .sidebar-brand i {
            font-size: 2rem;
            transition: all 0.3s ease;
        }

        .sidebar-brand:hover {
            color: var(--orange-hover) !important;
        }

        .sidebar-brand:hover i {
            transform: rotate(180deg);
        }

        .sidebar-toggle {
            width: 36px;
            height: 36px;
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.2);
            border-radius: 8px;
            color: var(--orange-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: var(--orange-primary);
            color: white;
        }

        /* User Section in Sidebar */
        .sidebar-user {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-dark);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: rgba(249, 115, 22, 0.1);
            border: 2px solid var(--orange-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--orange-primary);
            font-size: 1.8rem;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            background: var(--orange-primary);
            color: white;
            transform: scale(1.05);
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            display: inline-block;
            padding: 0.2rem 0.75rem;
            background: rgba(249, 115, 22, 0.1);
            color: var(--orange-primary);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Navigation Menu */
        .sidebar-nav {
            padding: 1.5rem 0;
            list-style: none;
        }

        .nav-section {
            padding: 0 1rem;
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            color: var(--text-secondary);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            color: var(--text-secondary) !important;
            text-decoration: none;
            border-radius: 10px;
            margin: 0.25rem 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .sidebar-link span {
            flex: 1;
            font-weight: 500;
        }

        .sidebar-link .badge {
            margin-left: auto;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.2), transparent);
            transform: translate(-50%, -50%);
            transition: all 0.5s ease;
            border-radius: 50%;
            z-index: -1;
        }

        .sidebar-link:hover {
            color: var(--orange-primary) !important;
            background: rgba(249, 115, 22, 0.1);
            transform: translateX(5px);
        }

        .sidebar-link:hover::before {
            width: 200px;
            height: 200px;
        }

        .sidebar-link:hover i {
            transform: scale(1.1);
            color: var(--orange-primary);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, var(--orange-primary), #f97316);
            color: white !important;
            box-shadow: 0 4px 10px rgba(249, 115, 22, 0.3);
        }

        .sidebar-link.active i {
            color: white !important;
        }

        .sidebar-link.active:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 15px rgba(249, 115, 22, 0.4);
        }

        /* Logout Button */
        .logout-item {
            margin-top: auto;
            padding: 1.5rem;
        }

        .logout-btn {
            width: 100%;
            padding: 0.75rem;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-secondary) 100%);
            min-height: 100vh;
            position: relative;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        .main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 300px;
            background: radial-gradient(circle at 20% 20%, rgba(249, 115, 22, 0.05), transparent 50%);
            pointer-events: none;
        }

        /* Content Header */
        .content-header {
            background: var(--dark-card);
            border-bottom: 1px solid var(--border-dark);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .breadcrumb {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
            border-left: 4px solid #10b981;
            color: #d1fae5;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
            border-left: 4px solid #ef4444;
            color: #fee2e2;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }

        /* Container */
        .container-fluid {
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Badge Styles */
        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        #unread-count {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border-dark);
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--orange-primary);
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-dark);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--orange-primary);
        }

        /* Animation for new messages */
        @keyframes newMessage {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }

        #unread-count.new {
            animation: newMessage 0.5s ease;
        }

        /* Glassmorphism Effects */
        .glass-effect {
            background: rgba(30, 35, 41, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(249, 115, 22, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1050;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .container-fluid {
                padding: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a class="sidebar-brand" href="{{ url('/') }}">
                    <i class="fas fa-cog"></i>
                    <span>{{ config('app.name', 'PSRS') }}</span>
                </a>
                <div class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
                    <i class="fas fa-chevron-left"></i>
                </div>
            </div>

            @auth
                <!-- User Info -->
                <div class="sidebar-user">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <ul class="sidebar-nav">
                    @if(auth()->user()->isAdmin())
                        <li class="nav-section">
                            <div class="nav-section-title">Main</div>
                            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}" data-tooltip="Dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-section">
                            <div class="nav-section-title">Management</div>
                            <a class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                               href="{{ route('categories.index') }}" data-tooltip="Categories">
                                <i class="fas fa-tags"></i>
                                <span>Categories</span>
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('requests.index') && !request()->routeIs('requests.create') ? 'active' : '' }}" 
                               href="{{ route('requests.index') }}" data-tooltip="All Requests">
                                <i class="fas fa-clipboard-list"></i>
                                <span>All Requests</span>
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('faqs.admin') ? 'active' : '' }}" 
                               href="{{ route('faqs.admin') }}" data-tooltip="Manage FAQs">
                                <i class="fas fa-question-circle"></i>
                                <span>Manage FAQs</span>
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('feedback.index') ? 'active' : '' }}" 
                               href="{{ route('feedback.index') }}" data-tooltip="Feedback">
                                <i class="fas fa-star"></i>
                                <span>Feedback</span>
                            </a>
                        </li>
                    @elseif(auth()->user()->isStaff())
                        <li class="nav-section">
                            <div class="nav-section-title">Main</div>
                            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}" data-tooltip="Dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('requests.index') ? 'active' : '' }}" 
                               href="{{ route('requests.index') }}" data-tooltip="Assigned Requests">
                                <i class="fas fa-clipboard-list"></i>
                                <span>Assigned Requests</span>
                            </a>
                        </li>
                    @else
                        <li class="nav-section">
                            <div class="nav-section-title">Main</div>
                            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}" data-tooltip="Dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-section">
                            <div class="nav-section-title">Requests</div>
                            <a class="sidebar-link {{ request()->routeIs('requests.create') ? 'active' : '' }}" 
                               href="{{ route('requests.create') }}" data-tooltip="New Request">
                                <i class="fas fa-plus-circle"></i>
                                <span>New Request</span>
                            </a>
                            <a class="sidebar-link {{ request()->routeIs('requests.index') ? 'active' : '' }}" 
                               href="{{ route('requests.index') }}" data-tooltip="My Requests">
                                <i class="fas fa-clipboard-list"></i>
                                <span>My Requests</span>
                            </a>
                        </li>
                    @endif

                    <!-- Common Links -->
                    <li class="nav-section">
                        <div class="nav-section-title">Support</div>
                        <a class="sidebar-link {{ request()->routeIs('faqs.index') ? 'active' : '' }}" 
                           href="{{ route('faqs.index') }}" data-tooltip="FAQ">
                            <i class="fas fa-question"></i>
                            <span>FAQ</span>
                        </a>
                        <a class="sidebar-link {{ request()->routeIs('messages.*') ? 'active' : '' }}" 
                           href="{{ route('messages.index') }}" data-tooltip="Messages">
                            <i class="fas fa-envelope"></i>
                            <span>Messages</span>
                            <span class="badge bg-danger" id="unread-count" style="display: none;">0</span>
                        </a>
                    </li>

                    <li class="nav-section">
                        <div class="nav-section-title">Account</div>
                        <a class="sidebar-link" href="{{ route('profile.edit') }}" data-tooltip="Profile">
                            <i class="fas fa-user-edit"></i>
                            <span>Profile</span>
                        </a>
                        <a class="sidebar-link" href="{{ route('profile.edit') }}#password" data-tooltip="Security">
                            <i class="fas fa-key"></i>
                            <span>Security</span>
                        </a>
                    </li>
                </ul>

                <!-- Logout Button -->
                <div class="logout-item">
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button type="submit" class="logout-btn" data-tooltip="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @else
                <!-- Guest Links -->
                <ul class="sidebar-nav">
                    <li class="nav-section">
                        <a class="sidebar-link" href="{{ route('login') }}" data-tooltip="Login">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                        <a class="sidebar-link" href="{{ route('register') }}" data-tooltip="Register">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>
                        <a class="sidebar-link" href="{{ route('faqs.index') }}" data-tooltip="FAQ">
                            <i class="fas fa-question"></i>
                            <span>FAQ</span>
                        </a>
                    </li>
                </ul>
            @endauth
        </div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Content Header -->
            <div class="content-header d-flex align-items-center">
                <button class="sidebar-toggle d-md-none me-3" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                    <div class="breadcrumb">@yield('breadcrumb')</div>
                </div>
            </div>

            <!-- Alerts -->
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Sidebar state
        let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

        // Initialize sidebar
        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.querySelector('#sidebarToggle i');
            
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                if (toggleBtn) toggleBtn.className = 'fas fa-chevron-right';
            } else {
                if (toggleBtn) toggleBtn.className = 'fas fa-chevron-left';
            }
        }

        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.querySelector('#sidebarToggle i');
            
            sidebarCollapsed = !sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
            
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                toggleBtn.className = 'fas fa-chevron-right';
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
                toggleBtn.className = 'fas fa-chevron-left';
            }
        }

        // Toggle mobile sidebar
        function toggleMobileSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Check for unread messages
        function checkUnreadMessages() {
            $.get('{{ route("messages.unread") }}', function(data) {
                const badge = $('#unread-count');
                if (data.count > 0) {
                    badge.text(data.count).show().addClass('new');
                    setTimeout(() => badge.removeClass('new'), 500);
                    
                    // Update page title with notification
                    if (document.title.indexOf('(') === -1) {
                        document.title = `(${data.count}) ${document.title}`;
                    }
                } else {
                    badge.hide();
                    // Remove notification count from title
                    document.title = document.title.replace(/^\(\d+\)\s/, '');
                }
            });
        }

        // Auto-dismiss alerts
        function initAlerts() {
            $('.alert').each(function() {
                const alert = $(this);
                setTimeout(function() {
                    alert.fadeOut(500, function() {
                        $(this).alert('close');
                    });
                }, 5000);
            });
        }

        $(document).ready(function() {
            initSidebar();
            checkUnreadMessages();
            initAlerts();
            
            // Check for unread messages every 30 seconds
            setInterval(checkUnreadMessages, 30000);
            
            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() <= 768) {
                    if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('.sidebar-toggle').length) {
                        $('#sidebar').removeClass('show');
                    }
                }
            });
        });

        // Handle browser back/forward cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>