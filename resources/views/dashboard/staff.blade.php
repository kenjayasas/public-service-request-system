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
                            <i class="fas fa-user-tie me-2" style="color: var(--orange-primary);"></i>
                            Staff Dashboard
                        </h2>
                        <p class="welcome-subtitle">Welcome back, {{ Auth::user()->name }}! Here are your assigned requests.</p>
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
        <div class="col-xl-4 col-md-6">
            <div class="stat-card assigned-requests">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $assignedRequests->total() }}</h3>
                    <p>Total Assigned</p>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-clock"></i>
                    <span>{{ $pendingCount }} pending</span>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="stat-card inprogress-requests">
                <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $inProgressCount }}</h3>
                    <p>In Progress</p>
                </div>
                <div class="stat-trend warning">
                    <i class="fas fa-hourglass-half"></i>
                    <span>Active now</span>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="stat-card completed-requests">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $completedCount }}</h3>
                    <p>Completed</p>
                </div>
                <div class="stat-trend success">
                    <i class="fas fa-star"></i>
                    <span>Well done!</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Requests -->
    <div class="row">
        <div class="col-12">
            <div class="requests-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>
                            <i class="fas fa-clipboard-list me-2" style="color: var(--orange-primary);"></i>
                            My Assigned Requests
                        </h5>
                        <div class="header-actions">
                            <select class="filter-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($assignedRequests->count() > 0)
                        <div class="request-grid">
                            @foreach($assignedRequests as $request)
                                <div class="request-card" data-status="{{ $request->status }}">
                                    <div class="request-card-header">
                                        <div class="request-id-badge">
                                            #{{ $request->id }}
                                        </div>
                                        <span class="status-badge status-{{ $request->status }}">
                                            <i class="fas fa-circle me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </div>

                                    <div class="request-card-body">
                                        <h6 class="request-title">
                                            <a href="{{ route('requests.show', $request) }}">
                                                {{ $request->title }}
                                            </a>
                                        </h6>

                                        <div class="request-meta">
                                            <div class="meta-item">
                                                <i class="fas fa-user"></i>
                                                <span>{{ $request->user->name }}</span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="fas fa-tag"></i>
                                                <span>{{ $request->category->name }}</span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span>{{ Str::limit($request->location, 20) }}</span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="far fa-clock"></i>
                                                <span>{{ $request->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        <p class="request-description">
                                            {{ Str::limit($request->description, 100) }}
                                        </p>
                                    </div>

                                    <div class="request-card-footer">
                                        <div class="action-buttons">
                                            <a href="{{ route('requests.show', $request) }}" 
                                               class="btn-action view" data-tooltip="View Details">
                                                <i class="fas fa-eye"></i>
                                                <span>View</span>
                                            </a>
                                            <button onclick="updateStatus({{ $request->id }}, 'completed')" 
                                                    class="btn-action complete" data-tooltip="Mark as Completed">
                                                <i class="fas fa-check"></i>
                                                <span>Complete</span>
                                            </button>
                                            <button onclick="openChat({{ $request->user_id }}, {{ $request->id }})" 
                                                    class="btn-action message" data-tooltip="Send Message">
                                                <i class="fas fa-envelope"></i>
                                                <span>Message</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pagination-wrapper">
                            {{ $assignedRequests->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h5>No Assigned Requests</h5>
                            <p>You don't have any requests assigned to you yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickMessageForm">
                    @csrf
                    <input type="hidden" name="receiver_id" id="receiver_id">
                    <input type="hidden" name="service_request_id" id="request_id">
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendQuickMessage()">Send Message</button>
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
    }

    .welcome-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .welcome-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
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
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--orange-primary);
        box-shadow: 0 15px 30px rgba(249, 115, 22, 0.15);
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

    .assigned-requests .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
    }

    .inprogress-requests .stat-icon {
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

    .stat-trend.warning {
        color: #f59e0b;
    }

    .stat-trend.success {
        color: #10b981;
    }

    /* Requests Card */
    .requests-card {
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

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .filter-select {
        background: var(--input-bg);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
        padding: 0.5rem 2rem 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.9rem;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23f97316' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
    }

    .filter-select:hover {
        border-color: var(--orange-primary);
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    /* Request Grid */
    .request-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem;
    }

    .request-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .request-card:hover {
        transform: translateY(-5px);
        border-color: var(--orange-primary);
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.1);
    }

    .request-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid var(--border-dark);
        background: rgba(0, 0, 0, 0.2);
    }

    .request-id-badge {
        background: var(--orange-primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .request-card-body {
        padding: 1rem;
    }

    .request-title {
        margin-bottom: 1rem;
    }

    .request-title a {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .request-title a:hover {
        color: var(--orange-primary);
    }

    .request-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        margin-bottom: 1rem;
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

    .request-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 0;
    }

    .request-card-footer {
        padding: 1rem;
        border-top: 1px solid var(--border-dark);
        background: rgba(0, 0, 0, 0.1);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem;
        background: transparent;
        border: 1px solid var(--border-dark);
        border-radius: 8px;
        color: var(--text-secondary);
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-action:hover {
        background: var(--orange-primary);
        color: white;
        border-color: var(--orange-primary);
    }

    .btn-action.complete:hover {
        background: #10b981;
        border-color: #10b981;
    }

    .btn-action.message:hover {
        background: #3b82f6;
        border-color: #3b82f6;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        background: rgba(249, 115, 22, 0.1);
        border: 2px dashed var(--orange-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3rem;
        color: var(--orange-primary);
    }

    .empty-state h5 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-secondary);
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        border-top: 1px solid var(--border-dark);
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
</style>
@endpush

@push('scripts')
<script>
    // Filter by status
    $('#statusFilter').on('change', function() {
        const status = $(this).val();
        $('.request-card').each(function() {
            if (!status || $(this).data('status') === status) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Update status
    function updateStatus(requestId, status) {
        if (confirm('Are you sure you want to mark this request as completed?')) {
            $.ajax({
                url: `/requests/${requestId}`,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error updating status. Please try again.');
                }
            });
        }
    }

    // Open chat modal
    function openChat(userId, requestId) {
        $('#receiver_id').val(userId);
        $('#request_id').val(requestId);
        $('#messageModal').modal('show');
    }

    // Send quick message
    function sendQuickMessage() {
        const form = $('#quickMessageForm');
        
        $.ajax({
            url: '{{ route("messages.store") }}',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#messageModal').modal('hide');
                alert('Message sent successfully!');
            },
            error: function(xhr) {
                alert('Error sending message. Please try again.');
            }
        });
    }
</script>
@endpush