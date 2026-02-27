@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="welcome-title">
                            <i class="fas fa-home me-2" style="color: var(--orange-primary);"></i>
                            Welcome back, {{ Auth::user()->name }}!
                        </h2>
                        <p class="welcome-subtitle">Here's what's happening with your service requests</p>
                    </div>
                    <div class="date-badge">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ now()->format('l, F j, Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card total-requests">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $totalRequests }}</h3>
                    <p>Total Requests</p>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>12% increase</span>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('requests.index') }}" class="stat-link">
                        View all <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card pending-requests">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $pendingRequests }}</h3>
                    <p>Pending</p>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-clock"></i>
                    <span>Awaiting review</span>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('requests.index', ['status' => 'pending']) }}" class="stat-link">
                        View pending <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card inprogress-requests">
                <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $inProgressRequests }}</h3>
                    <p>In Progress</p>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-users"></i>
                    <span>Staff assigned</span>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('requests.index', ['status' => 'in_progress']) }}" class="stat-link">
                        View active <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card completed-requests">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $completedRequests }}</h3>
                    <p>Completed</p>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-star"></i>
                    <span>Rate your experience</span>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('requests.index', ['status' => 'completed']) }}" class="stat-link">
                        View completed <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Status Overview -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="action-card">
                <div class="card-header-custom">
                    <h5>
                        <i class="fas fa-bolt me-2" style="color: var(--orange-primary);"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="action-grid">
                    <a href="{{ route('requests.create') }}" class="action-item">
                        <div class="action-icon bg-gradient">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <span>New Request</span>
                        <small>Submit a service request</small>
                    </a>
                    
                    <a href="{{ route('requests.index') }}" class="action-item">
                        <div class="action-icon bg-info-gradient">
                            <i class="fas fa-list"></i>
                        </div>
                        <span>All Requests</span>
                        <small>View your history</small>
                    </a>
                    
                    <a href="{{ route('messages.index') }}" class="action-item">
                        <div class="action-icon bg-success-gradient">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <span>Messages</span>
                        <small>Chat with staff</small>
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="action-item">
                        <div class="action-icon bg-warning-gradient">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <span>Profile</span>
                        <small>Update settings</small>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="status-card">
                <div class="card-header-custom">
                    <h5>
                        <i class="fas fa-chart-pie me-2" style="color: var(--orange-primary);"></i>
                        Request Status Overview
                    </h5>
                </div>
                <div class="status-grid">
                    <div class="status-item">
                        <div class="status-progress">
                            <div class="progress-circle" data-progress="{{ $totalRequests > 0 ? ($pendingRequests/$totalRequests)*100 : 0 }}">
                                <span>{{ $pendingRequests }}</span>
                            </div>
                        </div>
                        <h6>Pending</h6>
                        <p class="text-muted">{{ $totalRequests > 0 ? round(($pendingRequests/$totalRequests)*100) : 0 }}% of total</p>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-progress">
                            <div class="progress-circle" data-progress="{{ $totalRequests > 0 ? ($inProgressRequests/$totalRequests)*100 : 0 }}">
                                <span>{{ $inProgressRequests }}</span>
                            </div>
                        </div>
                        <h6>In Progress</h6>
                        <p class="text-muted">{{ $totalRequests > 0 ? round(($inProgressRequests/$totalRequests)*100) : 0 }}% of total</p>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-progress">
                            <div class="progress-circle" data-progress="{{ $totalRequests > 0 ? ($completedRequests/$totalRequests)*100 : 0 }}">
                                <span>{{ $completedRequests }}</span>
                            </div>
                        </div>
                        <h6>Completed</h6>
                        <p class="text-muted">{{ $totalRequests > 0 ? round(($completedRequests/$totalRequests)*100) : 0 }}% of total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests & Activity -->
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="recent-requests-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>
                            <i class="fas fa-history me-2" style="color: var(--orange-primary);"></i>
                            Recent Requests
                        </h5>
                        <a href="{{ route('requests.index') }}" class="view-all-link">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($recentRequests->count() > 0)
                        <div class="request-timeline">
                            @foreach($recentRequests as $request)
                                <div class="timeline-item">
                                    <div class="timeline-icon status-{{ $request->status }}">
                                        @switch($request->status)
                                            @case('pending')
                                                <i class="fas fa-clock"></i>
                                                @break
                                            @case('in_progress')
                                                <i class="fas fa-spinner"></i>
                                                @break
                                            @case('completed')
                                                <i class="fas fa-check-circle"></i>
                                                @break
                                            @case('rejected')
                                                <i class="fas fa-times-circle"></i>
                                                @break
                                        @endswitch
                                    </div>
                                    
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('requests.show', $request) }}" class="request-title">
                                                        {{ $request->title }}
                                                    </a>
                                                </h6>
                                                <div class="request-meta">
                                                    <span class="badge-custom category-badge">
                                                        <i class="fas fa-tag me-1"></i>
                                                        {{ $request->category->name }}
                                                    </span>
                                                    <span class="badge-custom status-badge status-{{ $request->status }}">
                                                        <i class="fas fa-circle me-1"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <small class="time-ago">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $request->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        
                                        <p class="request-description">
                                            {{ Str::limit($request->description, 100) }}
                                        </p>
                                        
                                        <div class="request-footer">
                                            <div class="request-location">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $request->location }}
                                            </div>
                                            
                                            @if($request->assignedStaff)
                                                <div class="assigned-staff">
                                                    <i class="fas fa-user-tie me-1"></i>
                                                    Assigned: {{ $request->assignedStaff->name }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($request->status === 'completed' && !$request->feedback)
                                            <div class="feedback-prompt">
                                                <a href="{{ route('requests.show', $request) }}#feedback" class="btn-feedback">
                                                    <i class="fas fa-star me-1"></i>
                                                    Rate this service
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h5>No Requests Yet</h5>
                            <p>You haven't submitted any service requests. Get started by creating your first request.</p>
                            <a href="{{ route('requests.create') }}" class="btn-primary-custom">
                                <i class="fas fa-plus-circle me-2"></i>
                                Submit Your First Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Recent Activity -->
            <div class="activity-card mb-4">
                <div class="card-header-custom">
                    <h5>
                        <i class="fas fa-bell me-2" style="color: var(--orange-primary);"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="activity-list">
                    @if($recentRequests->count() > 0)
                        @foreach($recentRequests->take(3) as $request)
                            <div class="activity-item">
                                <div class="activity-icon status-{{ $request->status }}">
                                    @switch($request->status)
                                        @case('pending')
                                            <i class="fas fa-clock"></i>
                                            @break
                                        @case('in_progress')
                                            <i class="fas fa-spinner"></i>
                                            @break
                                        @case('completed')
                                            <i class="fas fa-check-circle"></i>
                                            @break
                                        @case('rejected')
                                            <i class="fas fa-times-circle"></i>
                                            @break
                                    @endswitch
                                </div>
                                <div class="activity-content">
                                    <p class="mb-1">
                                        <strong>{{ $request->title }}</strong>
                                    </p>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $request->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="activity-footer">
                            <a href="{{ route('requests.index') }}" class="view-all-activity">
                                View all activity <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x mb-3" style="color: var(--text-muted);"></i>
                            <p class="text-muted">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Help & Support -->
            <div class="support-card">
                <div class="support-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h5>Need Help?</h5>
                <p>Our support team is here to assist you with any questions or concerns.</p>
                <div class="support-options">
                    <a href="{{ route('faqs.index') }}" class="support-link">
                        <i class="fas fa-question-circle"></i>
                        FAQs
                    </a>
                    <a href="{{ route('messages.index') }}" class="support-link">
                        <i class="fas fa-envelope"></i>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Welcome Card */
    .welcome-card {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.05));
        transform: skewX(-20deg) translateX(100px);
    }

    .welcome-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .welcome-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 0;
    }

    .date-badge {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 500;
        border: 1px solid rgba(249, 115, 22, 0.2);
    }

    /* Stat Cards */
    .stat-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--orange-primary);
        box-shadow: 0 15px 30px rgba(249, 115, 22, 0.15);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.1), transparent);
        transition: all 0.5s ease;
    }

    .stat-card:hover::after {
        transform: scale(1.2);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.8rem;
    }

    .total-requests .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
    }

    .pending-requests .stat-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
    }

    .inprogress-requests .stat-icon {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        box-shadow: 0 10px 20px rgba(6, 182, 212, 0.3);
    }

    .completed-requests .stat-icon {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }

    .stat-details h3 {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-details p {
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .stat-trend {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .stat-footer {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-dark);
    }

    .stat-link {
        color: var(--orange-primary);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .stat-link:hover {
        color: var(--orange-hover);
        padding-left: 5px;
    }

    /* Action Card */
    .action-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
    }

    .card-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-dark);
        background: rgba(0, 0, 0, 0.2);
    }

    .card-header-custom h5 {
        margin-bottom: 0;
        color: var(--text-primary);
        font-weight: 600;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 1.5rem;
    }

    .action-item {
        text-decoration: none;
        padding: 1.25rem 1rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .action-item:hover {
        transform: translateY(-5px);
        border-color: var(--orange-primary);
        background: rgba(249, 115, 22, 0.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        font-size: 1.5rem;
        color: white;
    }

    .bg-gradient {
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
    }

    .bg-info-gradient {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .bg-success-gradient {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .bg-warning-gradient {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .action-item span {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .action-item small {
        color: var(--text-secondary);
        font-size: 0.75rem;
    }

    /* Status Overview */
    .status-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        padding: 1.5rem;
    }

    .status-item {
        text-align: center;
    }

    .progress-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: conic-gradient(var(--orange-primary) 0deg, var(--border-dark) 0deg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        position: relative;
    }

    .progress-circle::before {
        content: '';
        position: absolute;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--dark-card);
    }

    .progress-circle span {
        position: relative;
        z-index: 1;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .status-item h6 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    /* Timeline */
    .request-timeline {
        position: relative;
    }

    .request-timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-dark);
    }

    .timeline-item {
        position: relative;
        padding-left: 50px;
        margin-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 1;
    }

    .timeline-icon.status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .timeline-icon.status-in_progress {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .timeline-icon.status-completed {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .timeline-icon.status-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .timeline-content {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .timeline-content:hover {
        border-color: var(--orange-primary);
        background: rgba(249, 115, 22, 0.05);
    }

    .request-title {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .request-title:hover {
        color: var(--orange-primary);
    }

    .badge-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .category-badge {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
    }

    .status-badge {
        margin-left: 0.5rem;
    }

    .status-badge.status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .status-badge.status-in_progress {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }

    .status-badge.status-completed {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-badge.status-rejected {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .time-ago {
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    .request-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .request-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .feedback-prompt {
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border-dark);
    }

    .btn-feedback {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 1rem;
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 20px;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-feedback:hover {
        background: var(--orange-primary);
        color: white;
    }

    /* Activity Card */
    .activity-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
    }

    .activity-list {
        padding: 1.25rem;
    }

    .activity-item {
        display: flex;
        align-items: start;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-dark);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-content p {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .activity-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border-dark);
        text-align: center;
    }

    .view-all-activity {
        color: var(--orange-primary);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .view-all-activity:hover {
        color: var(--orange-hover);
        padding-left: 5px;
    }

    /* Support Card */
    .support-card {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .support-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.1), transparent);
        border-radius: 50%;
    }

    .support-icon {
        width: 60px;
        height: 60px;
        background: rgba(249, 115, 22, 0.1);
        border: 2px solid var(--orange-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        color: var(--orange-primary);
    }

    .support-card h5 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .support-card p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .support-options {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .support-link {
        padding: 0.5rem 1rem;
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .support-link:hover {
        background: var(--orange-primary);
        color: white;
        transform: translateY(-3px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: rgba(249, 115, 22, 0.1);
        border: 2px dashed var(--orange-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        color: var(--orange-primary);
    }

    .empty-state h5 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
    }

    .btn-primary-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
    }

    .view-all-link {
        color: var(--orange-primary);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .view-all-link:hover {
        background: var(--orange-primary);
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .welcome-title {
            font-size: 1.5rem;
        }
        
        .action-grid {
            grid-template-columns: 1fr;
        }
        
        .status-grid {
            grid-template-columns: 1fr;
        }
        
        .request-footer {
            flex-direction: column;
            align-items: start;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize progress circles
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.progress-circle').forEach(circle => {
            const progress = circle.dataset.progress;
            const degrees = (progress / 100) * 360;
            circle.style.background = `conic-gradient(var(--orange-primary) ${degrees}deg, var(--border-dark) ${degrees}deg)`;
        });
    });

    // Auto-refresh data every 30 seconds (optional)
    // setInterval(function() {
    //     location.reload();
    // }, 30000);
</script>
@endpush