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
                            <i class="fas fa-crown me-2" style="color: var(--orange-primary);"></i>
                            Admin Dashboard
                        </h2>
                        <p class="welcome-subtitle">Welcome back, {{ Auth::user()->name }}! Here's your system overview.</p>
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
            <div class="stat-card total-users">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12% this month</span>
                </div>
                <div class="stat-footer">
                    <a href="#" class="stat-link">
                        View all users <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

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
                    <i class="fas fa-chart-line"></i>
                    <span>{{ $pendingRequests }} pending</span>
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
                <div class="stat-trend warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Needs attention</span>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('requests.index', ['status' => 'pending']) }}" class="stat-link">
                        View pending <i class="fas fa-arrow-right ms-1"></i>
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
                <div class="stat-trend success">
                    <i class="fas fa-star"></i>
                    <span>This month</span>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('requests.index', ['status' => 'completed']) }}" class="stat-link">
                        View completed <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="chart-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>
                            <i class="fas fa-chart-line me-2" style="color: var(--orange-primary);"></i>
                            Monthly Statistics
                        </h5>
                        <div class="chart-filters">
                            <select class="chart-filter" id="monthFilter">
                                <option value="6">Last 6 months</option>
                                <option value="12" selected>Last 12 months</option>
                                <option value="24">Last 24 months</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="chart-card">
                <div class="card-header-custom">
                    <h5>
                        <i class="fas fa-chart-pie me-2" style="color: var(--orange-primary);"></i>
                        Requests by Category
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="row g-4">
        <div class="col-xl-4">
            <!-- Quick Actions -->
            <div class="action-card mb-4">
                <div class="card-header-custom">
                    <h5>
                        <i class="fas fa-bolt me-2" style="color: var(--orange-primary);"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="action-grid">
                    <a href="{{ route('categories.create') }}" class="action-item">
                        <div class="action-icon bg-gradient">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <span>New Category</span>
                        <small>Create category</small>
                    </a>
                    
                    <a href="{{ route('faqs.create') }}" class="action-item">
                        <div class="action-icon bg-info-gradient">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <span>New FAQ</span>
                        <small>Add FAQ</small>
                    </a>
                    
                    <a href="{{ route('requests.index') }}" class="action-item">
                        <div class="action-icon bg-success-gradient">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <span>All Requests</span>
                        <small>Manage requests</small>
                    </a>
                    
                    <a href="{{ route('feedback.index') }}" class="action-item">
                        <div class="action-icon bg-warning-gradient">
                            <i class="fas fa-star"></i>
                        </div>
                        <span>Feedback</span>
                        <small>View ratings</small>
                    </a>
                </div>
            </div>

            <!-- System Health -->
            <div class="health-card">
                <h5>
                    <i class="fas fa-heartbeat me-2" style="color: var(--orange-primary);"></i>
                    System Health
                </h5>
                <div class="health-stats">
                    <div class="health-item">
                        <div class="health-label">
                            <span>Server Load</span>
                            <span class="health-value">45%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 45%"></div>
                        </div>
                    </div>
                    <div class="health-item">
                        <div class="health-label">
                            <span>Database</span>
                            <span class="health-value">2.3 GB</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="health-item">
                        <div class="health-label">
                            <span>Storage</span>
                            <span class="health-value">8.1 GB</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 32%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <!-- Recent Requests -->
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
                    @if(isset($recentRequests) && $recentRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Citizen</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                    <tr>
                                        <td>
                                            <span class="request-id">#{{ $request->id }}</span>
                                        </td>
                                        <td>
                                            <div class="request-title-cell">
                                                <strong>{{ Str::limit($request->title, 30) }}</strong>
                                                <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <i class="fas fa-user-circle"></i>
                                                {{ $request->user->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="category-badge">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $request->category->name }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'in_progress' => 'info',
                                                    'completed' => 'success',
                                                    'rejected' => 'danger'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Pending',
                                                    'in_progress' => 'In Progress',
                                                    'completed' => 'Completed',
                                                    'rejected' => 'Rejected'
                                                ];
                                            @endphp
                                            <span class="status-badge status-{{ $request->status }}">
                                                <i class="fas fa-circle me-1"></i>
                                                {{ $statusLabels[$request->status] ?? $request->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($request->assignedStaff)
                                                <div class="staff-info">
                                                    <i class="fas fa-user-tie"></i>
                                                    {{ $request->assignedStaff->name }}
                                                </div>
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('requests.show', $request) }}" 
                                                   class="btn-action view" data-tooltip="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('requests.edit', $request) }}" 
                                                   class="btn-action edit" data-tooltip="Edit Request">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h5>No Requests Yet</h5>
                            <p>There are no service requests in the system.</p>
                        </div>
                    @endif
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
        width: 400px;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.05));
        transform: skewX(-20deg) translateX(150px);
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
        color: white;
    }

    .total-users .stat-icon {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
    }

    .total-requests .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
    }

    .pending-requests .stat-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
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
    }

    .stat-trend.positive {
        color: #10b981;
    }

    .stat-trend.warning {
        color: #f59e0b;
    }

    .stat-trend.success {
        color: #10b981;
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

    /* Chart Cards */
    .chart-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
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

    .chart-filters {
        display: flex;
        gap: 0.5rem;
    }

    .chart-filter {
        background: var(--input-bg);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
        padding: 0.35rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .chart-filter:hover {
        border-color: var(--orange-primary);
    }

    .chart-filter:focus {
        outline: none;
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    /* Admin Table */
    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
    }

    .admin-table thead th {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-dark);
    }

    .admin-table tbody tr {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .admin-table tbody tr:hover {
        background: rgba(249, 115, 22, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .admin-table td {
        padding: 1rem;
        color: var(--text-primary);
        border: none;
    }

    .request-id {
        font-weight: 600;
        color: var(--orange-primary);
    }

    .request-title-cell {
        display: flex;
        flex-direction: column;
    }

    .request-title-cell small {
        font-size: 0.75rem;
    }

    .user-info, .staff-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-info i, .staff-info i {
        color: var(--orange-primary);
        font-size: 1.1rem;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        border-radius: 20px;
        font-size: 0.85rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
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

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-dark);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-action.view:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .btn-action.edit:hover {
        background: var(--orange-primary);
        color: white;
        border-color: var(--orange-primary);
    }

    /* Health Card */
    .health-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        padding: 1.5rem;
    }

    .health-card h5 {
        color: var(--text-primary);
        margin-bottom: 1.5rem;
    }

    .health-stats {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .health-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .health-label {
        display: flex;
        justify-content: space-between;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .health-value {
        color: var(--orange-primary);
        font-weight: 600;
    }

    .progress {
        height: 6px;
        background: var(--border-dark);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(90deg, var(--orange-primary), #f97316);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    /* Action Card */
    .action-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($monthlyStats ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'])) !!},
            datasets: [{
                label: 'Number of Requests',
                data: {!! json_encode(array_values($monthlyStats ?? [10, 15, 20, 25, 30, 35])) !!},
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#f97316',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#9ca3af'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#9ca3af'
                    }
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($requestsByCategory->pluck('name') ?? ['Road', 'Water', 'Waste']) !!},
            datasets: [{
                data: {!! json_encode($requestsByCategory->pluck('service_requests_count') ?? [30, 25, 45]) !!},
                backgroundColor: [
                    '#f97316',
                    '#3b82f6',
                    '#10b981',
                    '#8b5cf6',
                    '#f59e0b',
                    '#ef4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#9ca3af',
                        font: {
                            size: 12
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endpush