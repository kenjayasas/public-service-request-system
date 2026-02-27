@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="fas {{ auth()->user()->isAdmin() ? 'fa-clipboard-list' : (auth()->user()->isStaff() ? 'fa-tasks' : 'fa-file-alt') }}"></i>
                        </div>
                        <div>
                            <h2 class="page-title">
                                @if(auth()->user()->isAdmin())
                                    All Service Requests
                                @elseif(auth()->user()->isStaff())
                                    Assigned Requests
                                @else
                                    My Requests
                                @endif
                            </h2>
                            <p class="page-subtitle">
                                @if(auth()->user()->isAdmin())
                                    Manage and monitor all service requests from citizens
                                @elseif(auth()->user()->isStaff())
                                    View and manage requests assigned to you
                                @else
                                    Track and manage your submitted requests
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if(auth()->user()->isCitizen())
                        <a href="{{ route('requests.create') }}" class="btn-create">
                            <i class="fas fa-plus-circle me-2"></i>
                            New Request
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    @if(auth()->user()->isAdmin())
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $requests->total() }}</h3>
                    <p>Total Requests</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $requests->where('status', 'pending')->count() }}</h3>
                    <p>Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card in-progress">
                <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $requests->where('status', 'in_progress')->count() }}</h3>
                    <p>In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card completed">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $requests->where('status', 'completed')->count() }}</h3>
                    <p>Completed</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filters-card">
                <div class="filters-header">
                    <i class="fas fa-filter me-2"></i>
                    Filter Requests
                </div>
                <div class="filters-body">
                    <form method="GET" action="{{ route('requests.index') }}" class="filters-form">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">
                                    <i class="fas fa-tag me-1"></i>
                                    Status
                                </label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="category" class="form-label">
                                    <i class="fas fa-folder me-1"></i>
                                    Category
                                </label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="search" class="form-label">
                                    <i class="fas fa-search me-1"></i>
                                    Search
                                </label>
                                <div class="search-wrapper">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" name="search" id="search" class="form-control with-icon" 
                                           value="{{ request('search') }}" 
                                           placeholder="Search by title, description, or location...">
                                </div>
                            </div>
                            
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn-filter">
                                    <i class="fas fa-filter me-2"></i>
                                    Apply Filters
                                </button>
                                <a href="{{ route('requests.index') }}" class="btn-clear ms-2" data-tooltip="Clear filters">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="actions-bar">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="results-info">
                        <i class="fas fa-database me-2"></i>
                        Showing {{ $requests->firstItem() ?? 0 }} - {{ $requests->lastItem() ?? 0 }} 
                        of {{ $requests->total() }} requests
                    </div>
                    
                    <div class="action-buttons">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('requests.export.pdf') }}?{{ http_build_query(request()->all()) }}" 
                               class="btn-export" target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>
                                Export to PDF
                            </a>
                        @endif
                        
                        @if(request()->hasAny(['status', 'category', 'search']))
                            <span class="active-filters">
                                <i class="fas fa-info-circle me-1"></i>
                                Active filters applied
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Grid/Table -->
    <div class="row">
        <div class="col-12">
            <div class="requests-card">
                @if($requests->count() > 0)
                    <!-- Table View for larger screens -->
                    <div class="table-responsive d-none d-lg-block">
                        <table class="requests-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Request Details</th>
                                    @if(auth()->user()->isAdmin())
                                        <th>Citizen</th>
                                    @endif
                                    <th>Category</th>
                                    <th>Status</th>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                        <th>Assigned To</th>
                                    @endif
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                <tr class="request-row">
                                    <td>
                                        <span class="request-id">#{{ $request->id }}</span>
                                    </td>
                                    <td>
                                        <div class="request-info">
                                            <strong class="request-title">{{ $request->title }}</strong>
                                            <small class="request-desc text-secondary">
                                                {{ Str::limit($request->description, 60) }}
                                            </small>
                                            <div class="request-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $request->location }}
                                            </div>
                                        </div>
                                    </td>
                                    @if(auth()->user()->isAdmin())
                                        <td>
                                            <div class="citizen-info">
                                                <i class="fas fa-user-circle"></i>
                                                {{ $request->user->name }}
                                                <small class="d-block text-secondary">
                                                    {{ $request->user->email }}
                                                </small>
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        <span class="category-badge">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $request->category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => ['bg' => '#f59e0b20', 'text' => '#f59e0b', 'icon' => 'fa-clock'],
                                                'in_progress' => ['bg' => '#3b82f620', 'text' => '#3b82f6', 'icon' => 'fa-spinner fa-spin'],
                                                'completed' => ['bg' => '#10b98120', 'text' => '#10b981', 'icon' => 'fa-check-circle'],
                                                'rejected' => ['bg' => '#ef444420', 'text' => '#ef4444', 'icon' => 'fa-times-circle']
                                            ];
                                            $status = $statusColors[$request->status];
                                        @endphp
                                        <span class="status-badge" style="background: {{ $status['bg'] }}; color: {{ $status['text'] }};">
                                            <i class="fas {{ $status['icon'] }} me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                        <td>
                                            @if($request->assignedStaff)
                                                <div class="staff-info">
                                                    <i class="fas fa-user-tie"></i>
                                                    {{ $request->assignedStaff->name }}
                                                </div>
                                            @else
                                                <span class="unassigned-badge">
                                                    <i class="fas fa-user-slash me-1"></i>
                                                    Unassigned
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <div class="submitted-info">
                                            <i class="far fa-calendar-alt"></i>
                                            <span>{{ $request->created_at->format('M d, Y') }}</span>
                                            <small class="text-secondary d-block">
                                                {{ $request->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-cell">
                                            <a href="{{ route('requests.show', $request) }}" 
                                               class="btn-icon view" data-tooltip="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(auth()->user()->isAdmin() || 
                                                (auth()->user()->isStaff() && $request->assigned_staff_id == auth()->id()))
                                                <a href="{{ route('requests.edit', $request) }}" 
                                                   class="btn-icon edit" data-tooltip="Edit Request">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            
                                            @if(auth()->user()->isAdmin())
                                                <button class="btn-icon assign" 
                                                        onclick="openAssignModal({{ $request->id }})"
                                                        data-tooltip="Assign Staff">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Card View for mobile -->
                    <div class="request-cards d-lg-none">
                        @foreach($requests as $request)
                            <div class="request-card">
                                <div class="request-card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="request-id">#{{ $request->id }}</span>
                                        @php
                                            $statusColors = [
                                                'pending' => ['bg' => '#f59e0b20', 'text' => '#f59e0b'],
                                                'in_progress' => ['bg' => '#3b82f620', 'text' => '#3b82f6'],
                                                'completed' => ['bg' => '#10b98120', 'text' => '#10b981'],
                                                'rejected' => ['bg' => '#ef444420', 'text' => '#ef4444']
                                            ];
                                            $status = $statusColors[$request->status];
                                        @endphp
                                        <span class="status-badge" style="background: {{ $status['bg'] }}; color: {{ $status['text'] }};">
                                            <i class="fas {{ $statusColors[$request->status]['icon'] ?? 'fa-circle' }} me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="request-card-body">
                                    <h6 class="request-title">{{ $request->title }}</h6>
                                    <p class="request-description">{{ Str::limit($request->description, 100) }}</p>
                                    
                                    <div class="request-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-folder"></i>
                                            <span>{{ $request->category->name }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ Str::limit($request->location, 30) }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="far fa-clock"></i>
                                            <span>{{ $request->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                            <div class="meta-item">
                                                <i class="fas fa-user-tie"></i>
                                                <span>{{ $request->assignedStaff->name ?? 'Unassigned' }}</span>
                                            </div>
                                        @endif
                                        @if(auth()->user()->isAdmin())
                                            <div class="meta-item">
                                                <i class="fas fa-user"></i>
                                                <span>{{ $request->user->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="request-card-footer">
                                    <a href="{{ route('requests.show', $request) }}" class="btn-action">
                                        <i class="fas fa-eye me-2"></i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper">
                        {{ $requests->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas {{ auth()->user()->isCitizen() ? 'fa-file-alt' : 'fa-inbox' }}"></i>
                        </div>
                        <h3>No Requests Found</h3>
                        <p>
                            @if(auth()->user()->isCitizen())
                                You haven't submitted any service requests yet.
                                Get started by creating your first request.
                            @elseif(auth()->user()->isStaff())
                                No requests have been assigned to you yet.
                                Check back later for new assignments.
                            @else
                                No service requests match your criteria.
                                Try adjusting your filters.
                            @endif
                        </p>
                        
                        @if(auth()->user()->isCitizen())
                            <a href="{{ route('requests.create') }}" class="btn-empty-state">
                                <i class="fas fa-plus-circle me-2"></i>
                                Submit Your First Request
                            </a>
                        @elseif(request()->hasAny(['status', 'category', 'search']))
                            <a href="{{ route('requests.index') }}" class="btn-empty-state">
                                <i class="fas fa-times me-2"></i>
                                Clear Filters
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assign Staff Modal -->
@if(auth()->user()->isAdmin())
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2" style="color: var(--orange-primary);"></i>
                    Assign Staff Member
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignForm">
                    @csrf
                    <input type="hidden" name="request_id" id="modal_request_id">
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Select Staff Member</label>
                        <select name="staff_id" id="staff_select" class="form-select" required>
                            <option value="">Choose a staff member...</option>
                            @foreach(\App\Models\User::where('role', 'staff')->get() as $staff)
                                <option value="{{ $staff->id }}">
                                    {{ $staff->name }} ({{ $staff->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="assignStaff()">
                    <i class="fas fa-check me-2"></i>
                    Assign Staff
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        font-size: 2rem;
        color: white;
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 0;
    }

    .btn-create {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
    }

    .btn-create:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
    }

    /* Stats Cards */
    .stat-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        border-color: var(--orange-primary);
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.1);
    }

    .stat-card .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card.total .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .stat-card.pending .stat-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-card.in-progress .stat-icon {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .stat-card.completed .stat-icon {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-details h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-details p {
        color: var(--text-secondary);
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    /* Filters Card */
    .filters-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        overflow: hidden;
    }

    .filters-header {
        padding: 1rem 1.5rem;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-dark);
        color: var(--orange-primary);
        font-weight: 600;
    }

    .filters-body {
        padding: 1.5rem;
    }

    .filters-form .form-label {
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .filters-form .form-select,
    .filters-form .form-control {
        background-color: var(--input-bg);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
        border-radius: 10px;
        padding: 0.6rem 1rem;
    }

    .filters-form .form-select:focus,
    .filters-form .form-control:focus {
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        outline: none;
    }

    .search-wrapper {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        z-index: 1;
    }

    .form-control.with-icon {
        padding-left: 2.5rem;
    }

    .btn-filter {
        padding: 0.6rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
    }

    .btn-clear {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-dark);
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-clear:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    /* Actions Bar */
    .actions-bar {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 12px;
        padding: 1rem 1.5rem;
    }

    .results-info {
        color: var(--text-secondary);
        font-size: 0.95rem;
    }

    .results-info i {
        color: var(--orange-primary);
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        padding: 0.6rem 1.2rem;
        background: #10b98120;
        border: 1px solid #10b98140;
        border-radius: 10px;
        color: #10b981;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        background: #10b981;
        color: white;
        transform: translateY(-2px);
    }

    .active-filters {
        padding: 0.4rem 1rem;
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 20px;
        color: var(--orange-primary);
        font-size: 0.9rem;
        margin-left: 1rem;
    }

    /* Requests Card */
    .requests-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
    }

    /* Table View */
    .requests-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .requests-table thead th {
        padding: 1rem 1.5rem;
        background: rgba(0, 0, 0, 0.3);
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-dark);
    }

    .requests-table tbody tr {
        transition: all 0.3s ease;
    }

    .requests-table tbody tr:hover {
        background: rgba(249, 115, 22, 0.05);
    }

    .requests-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-dark);
        color: var(--text-primary);
    }

    .request-id {
        font-weight: 600;
        color: var(--orange-primary);
    }

    .request-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .request-title {
        color: var(--text-primary);
        font-size: 1rem;
    }

    .request-desc {
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .request-location {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .request-location i {
        color: var(--orange-primary);
        margin-right: 0.25rem;
        font-size: 0.7rem;
    }

    .citizen-info {
        display: flex;
        flex-direction: column;
    }

    .citizen-info i {
        color: var(--orange-primary);
        margin-right: 0.25rem;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.8rem;
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .staff-info {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--text-primary);
    }

    .staff-info i {
        color: var(--orange-primary);
    }

    .unassigned-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.8rem;
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .submitted-info {
        display: flex;
        flex-direction: column;
    }

    .submitted-info i {
        color: var(--orange-primary);
        margin-right: 0.25rem;
    }

    .action-cell {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-dark);
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-icon.view:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .btn-icon.edit:hover {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
    }

    .btn-icon.assign:hover {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    /* Card View (Mobile) */
    .request-cards {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .request-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        overflow: hidden;
    }

    .request-card-header {
        padding: 1rem;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-dark);
    }

    .request-card-body {
        padding: 1rem;
    }

    .request-card-footer {
        padding: 1rem;
        border-top: 1px solid var(--border-dark);
        background: rgba(0, 0, 0, 0.1);
    }

    .request-meta {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .meta-item i {
        color: var(--orange-primary);
        width: 16px;
    }

    .btn-action {
        display: block;
        width: 100%;
        padding: 0.75rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        background: rgba(249, 115, 22, 0.1);
        border: 2px dashed var(--orange-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3.5rem;
        color: var(--orange-primary);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h3 {
        color: var(--text-primary);
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        max-width: 400px;
        margin: 0 auto 1.5rem;
    }

    .btn-empty-state {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-empty-state:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.4);
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        border-top: 1px solid var(--border-dark);
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 0.25rem;
    }

    .page-link {
        padding: 0.5rem 0.75rem;
        background: transparent;
        border: 1px solid var(--border-dark);
        color: var(--text-secondary);
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: var(--orange-primary);
        color: white;
        border-color: var(--orange-primary);
    }

    .page-item.active .page-link {
        background: var(--orange-primary);
        color: white;
        border-color: var(--orange-primary);
    }

    /* Tooltip */
    [data-tooltip] {
        position: relative;
    }

    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 120%;
        left: 50%;
        transform: translateX(-50%);
        padding: 0.25rem 0.5rem;
        background: var(--dark-card);
        color: var(--text-primary);
        border: 1px solid var(--border-dark);
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 100;
    }

    [data-tooltip]:hover:before {
        opacity: 1;
        visibility: visible;
    }

    /* Modal */
    .modal-content {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
    }

    .modal-header {
        border-bottom: 1px solid var(--border-dark);
        padding: 1.5rem;
    }

    .modal-title {
        color: var(--text-primary);
        font-weight: 600;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-dark);
        padding: 1.5rem;
    }

    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .form-select {
        background-color: var(--input-bg);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
        padding: 0.75rem 1rem;
        border-radius: 10px;
    }

    .form-select:focus {
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        outline: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1rem;
        }
        
        .header-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            margin-right: 1rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .filters-body {
            padding: 1rem;
        }
        
        .btn-filter {
            width: 100%;
        }
        
        .actions-bar .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .active-filters {
            margin-left: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Open assign modal
    function openAssignModal(requestId) {
        $('#modal_request_id').val(requestId);
        $('#assignModal').modal('show');
    }

    // Assign staff
    function assignStaff() {
        const requestId = $('#modal_request_id').val();
        const staffId = $('#staff_select').val();

        if (!staffId) {
            alert('Please select a staff member.');
            return;
        }

        $.ajax({
            url: `/requests/${requestId}/assign-staff`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                staff_id: staffId
            },
            success: function(response) {
                $('#assignModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Error assigning staff. Please try again.');
            }
        });
    }

    // Auto-submit filters on change (optional)
    // $('.filters-form select').on('change', function() {
    //     $(this).closest('form').submit();
    // });

    // Clear search with debounce
    let searchTimer;
    $('#search').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            $(this).closest('form').submit();
        }, 500);
    });

    // Initialize tooltips
    $(document).ready(function() {
        // Add any initialization code here
    });
</script>
@endpush