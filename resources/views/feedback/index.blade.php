@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <h2 class="page-title">User Feedback</h2>
                        <p class="page-subtitle">Monitor and analyze feedback from citizens</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-comment"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $feedback->total() }}</h3>
                    <p>Total Feedback</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12% this month</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card rating">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-details">
                    @php
                        $avgRating = $feedback->avg('rating') ?? 0;
                    @endphp
                    <h3>{{ number_format($avgRating, 1) }}</h3>
                    <p>Average Rating</p>
                </div>
                <div class="stat-trend">
                    <div class="mini-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= round($avgRating) ? 'active' : '' }}"></i>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card positive">
                <div class="stat-icon">
                    <i class="fas fa-smile"></i>
                </div>
                <div class="stat-details">
                    @php
                        $positiveCount = $feedback->where('rating', '>=', 4)->count();
                        $positivePercent = $feedback->count() > 0 ? round(($positiveCount / $feedback->count()) * 100) : 0;
                    @endphp
                    <h3>{{ $positivePercent }}%</h3>
                    <p>Positive Feedback</p>
                </div>
                <div class="stat-trend">
                    <span>{{ $positiveCount }} responses</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card negative">
                <div class="stat-icon">
                    <i class="fas fa-frown"></i>
                </div>
                <div class="stat-details">
                    @php
                        $negativeCount = $feedback->where('rating', '<=', 2)->count();
                        $negativePercent = $feedback->count() > 0 ? round(($negativeCount / $feedback->count()) * 100) : 0;
                    @endphp
                    <h3>{{ $negativePercent }}%</h3>
                    <p>Needs Improvement</p>
                </div>
                <div class="stat-trend">
                    <span>{{ $negativeCount }} responses</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Charts Row -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="filters-card">
                <div class="filters-header">
                    <i class="fas fa-filter me-2"></i>
                    Filter Feedback
                </div>
                <div class="filters-body">
                    <form method="GET" action="{{ route('feedback.index') }}" class="filters-form">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select">
                                    <option value="">All Ratings</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date Range</label>
                                <select name="date_range" class="form-select">
                                    <option value="">All Time</option>
                                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                                    <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This Year</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <div class="search-wrapper">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control with-icon" 
                                           value="{{ request('search') }}"
                                           placeholder="Search comments...">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('feedback.index') }}" class="btn-clear">
                                        <i class="fas fa-times me-2"></i>
                                        Clear Filters
                                    </a>
                                    <button type="submit" class="btn-apply">
                                        <i class="fas fa-filter me-2"></i>
                                        Apply Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="rating-distribution-card">
                <h6 class="distribution-title">
                    <i class="fas fa-chart-pie me-2"></i>
                    Rating Distribution
                </h6>
                <div class="distribution-bars">
                    @for($star = 5; $star >= 1; $star--)
                        @php
                            $count = $feedback->where('rating', $star)->count();
                            $percentage = $feedback->count() > 0 ? round(($count / $feedback->count()) * 100) : 0;
                        @endphp
                        <div class="distribution-item">
                            <span class="star-label">{{ $star }} <i class="fas fa-star"></i></span>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: {{ $percentage }}%; background: {{ $star >= 4 ? '#10b981' : ($star >= 3 ? '#f59e0b' : '#ef4444') }}"></div>
                            </div>
                            <span class="percentage-label">{{ $percentage }}%</span>
                            <span class="count-label">({{ $count }})</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Table -->
    <div class="row">
        <div class="col-12">
            <div class="feedback-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2" style="color: var(--orange-primary);"></i>
                            Feedback List
                        </h5>
                        <div class="header-actions">
                            <span class="item-count">{{ $feedback->total() }} entries</span>
                            <button class="btn-export" onclick="exportFeedback()">
                                <i class="fas fa-download me-2"></i>
                                Export
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($feedback->count() > 0)
                        <div class="table-responsive">
                            <table class="feedback-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Request</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feedback as $item)
                                    <tr class="feedback-row">
                                        <td>
                                            <span class="feedback-id">#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <i class="fas fa-user-circle"></i>
                                                <div>
                                                    <strong>{{ $item->user->name }}</strong>
                                                    <small class="text-secondary d-block">{{ $item->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('requests.show', $item->serviceRequest) }}" class="request-link">
                                                <i class="fas fa-file-alt me-1"></i>
                                                {{ Str::limit($item->serviceRequest->title, 40) }}
                                                <small class="request-id">#{{ $item->serviceRequest->id }}</small>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="rating-display">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $item->rating ? 'rated' : '' }}"></i>
                                                @endfor
                                                <span class="rating-value">{{ $item->rating }}.0</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="comment-cell">
                                                @if($item->comment)
                                                    <div class="comment-preview">
                                                        "{{ Str::limit($item->comment, 60) }}"
                                                        @if(strlen($item->comment) > 60)
                                                            <span class="read-more" onclick="showFullComment('{{ addslashes($item->comment) }}')">
                                                                Read More
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="no-comment">No comment provided</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-cell">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $item->created_at->format('M d, Y') }}
                                                <small>{{ $item->created_at->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon view" 
                                                        onclick="viewFeedback({{ $item->id }})"
                                                        data-tooltip="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn-icon response" 
                                                        onclick="respondToFeedback({{ $item->id }})"
                                                        data-tooltip="Respond">
                                                    <i class="fas fa-reply"></i>
                                                </button>
                                                @if(auth()->user()->isAdmin())
                                                    <button class="btn-icon delete" 
                                                            onclick="deleteFeedback({{ $item->id }})"
                                                            data-tooltip="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper">
                            {{ $feedback->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h3>No Feedback Yet</h3>
                            <p>There is no feedback available at the moment. Feedback will appear here when citizens rate their completed requests.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Feedback Modal -->
<div class="modal fade" id="viewFeedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2" style="color: var(--orange-primary);"></i>
                    Feedback Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="feedbackDetailContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Respond Modal -->
<div class="modal fade" id="respondModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-reply me-2" style="color: var(--orange-primary);"></i>
                    Respond to Feedback
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="respondForm">
                    @csrf
                    <input type="hidden" name="feedback_id" id="response_feedback_id">
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Your Response</label>
                        <textarea name="response" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Response</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Comment Modal -->
<div class="modal fade" id="commentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Full Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="fullCommentText"></p>
            </div>
        </div>
    </div>
</div>
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

    /* Statistics Cards */
    .stat-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1.25rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        border-color: var(--orange-primary);
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .total .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .rating .stat-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .positive .stat-icon {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .negative .stat-icon {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .stat-details h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-details p {
        color: var(--text-secondary);
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .stat-trend {
        margin-top: 0.5rem;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stat-trend.positive {
        color: #10b981;
    }

    .mini-stars {
        display: flex;
        gap: 2px;
    }

    .mini-stars i {
        font-size: 0.7rem;
        color: var(--border-dark);
    }

    .mini-stars i.active {
        color: #fbbf24;
    }

    /* Filters Card */
    .filters-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
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

    .form-label {
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-select,
    .form-control {
        background-color: var(--input-bg);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
        border-radius: 8px;
        padding: 0.6rem 1rem;
    }

    .form-select:focus,
    .form-control:focus {
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
        color: var(--orange-primary);
    }

    .form-control.with-icon {
        padding-left: 2.5rem;
    }

    .btn-clear {
        padding: 0.6rem 1.5rem;
        background: transparent;
        border: 1px solid var(--border-dark);
        border-radius: 8px;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-clear:hover {
        border-color: #ef4444;
        color: #ef4444;
    }

    .btn-apply {
        padding: 0.6rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
    }

    /* Rating Distribution Card */
    .rating-distribution-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1.5rem;
        height: 100%;
    }

    .distribution-title {
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .distribution-bars {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .distribution-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .star-label {
        min-width: 50px;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .star-label i {
        color: #fbbf24;
        margin-left: 0.25rem;
        font-size: 0.8rem;
    }

    .progress-bar-container {
        flex: 1;
        height: 8px;
        background: var(--border-dark);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .percentage-label {
        min-width: 45px;
        color: var(--text-primary);
        font-size: 0.9rem;
        font-weight: 600;
    }

    .count-label {
        min-width: 40px;
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    /* Feedback Card */
    .feedback-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
    }

    .card-header-custom {
        padding: 1.25rem 1.5rem;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-dark);
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .item-count {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .btn-export {
        padding: 0.35rem 1rem;
        background: #10b98120;
        border: 1px solid #10b98140;
        border-radius: 8px;
        color: #10b981;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        background: #10b981;
        color: white;
    }

    /* Feedback Table */
    .feedback-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
    }

    .feedback-table thead th {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-dark);
    }

    .feedback-table tbody tr {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .feedback-table tbody tr:hover {
        background: rgba(249, 115, 22, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .feedback-table td {
        padding: 1rem;
        color: var(--text-primary);
        border: none;
    }

    .feedback-id {
        font-weight: 600;
        color: var(--orange-primary);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-info i {
        font-size: 2rem;
        color: var(--orange-primary);
    }

    .request-link {
        color: var(--text-primary);
        text-decoration: none;
        display: flex;
        flex-direction: column;
    }

    .request-link:hover {
        color: var(--orange-primary);
    }

    .request-id {
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    .rating-display {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .rating-display i {
        font-size: 1rem;
        color: var(--border-dark);
    }

    .rating-display i.rated {
        color: #fbbf24;
    }

    .rating-value {
        margin-left: 0.5rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .comment-cell {
        max-width: 250px;
    }

    .comment-preview {
        color: var(--text-secondary);
        font-style: italic;
        line-height: 1.5;
    }

    .read-more {
        color: var(--orange-primary);
        cursor: pointer;
        font-size: 0.8rem;
        margin-left: 0.5rem;
        text-decoration: underline;
    }

    .no-comment {
        color: var(--text-muted);
        font-style: italic;
    }

    .date-cell {
        display: flex;
        flex-direction: column;
    }

    .date-cell i {
        color: var(--orange-primary);
        margin-right: 0.25rem;
    }

    .date-cell small {
        color: var(--text-secondary);
        font-size: 0.75rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 1px solid var(--border-dark);
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-icon.view:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-icon.response:hover {
        background: #f59e0b;
        color: white;
    }

    .btn-icon.delete:hover {
        background: #ef4444;
        color: white;
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
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h3 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        max-width: 400px;
        margin: 0 auto;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        border-top: 1px solid var(--border-dark);
        display: flex;
        justify-content: center;
    }

    /* Modal */
    .modal-content {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
    }

    .modal-header {
        border-bottom: 1px solid var(--border-dark);
    }

    .modal-title {
        color: var(--text-primary);
    }

    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
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

    /* Responsive */
    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .feedback-table {
            font-size: 0.9rem;
        }
        
        .user-info {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .rating-display {
            flex-wrap: wrap;
        }
        
        .action-buttons {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // View feedback details
    function viewFeedback(id) {
        $.get(`/feedback/${id}`, function(data) {
            $('#feedbackDetailContent').html(data);
            $('#viewFeedbackModal').modal('show');
        });
    }

    // Respond to feedback
    function respondToFeedback(id) {
        $('#response_feedback_id').val(id);
        $('#respondModal').modal('show');
    }

    // Submit response
    $('#respondForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/feedback/respond',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#respondModal').modal('hide');
                showNotification('Response sent successfully', 'success');
            },
            error: function() {
                showNotification('Error sending response', 'error');
            }
        });
    });

    // Delete feedback
    function deleteFeedback(id) {
        Swal.fire({
            title: 'Delete Feedback?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            background: '#1e2329',
            color: '#f3f4f6'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/feedback/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        location.reload();
                    },
                    error: function() {
                        showNotification('Error deleting feedback', 'error');
                    }
                });
            }
        });
    }

    // Show full comment
    function showFullComment(comment) {
        $('#fullCommentText').text(comment);
        $('#commentModal').modal('show');
    }

    // Export feedback
    function exportFeedback() {
        const params = new URLSearchParams(window.location.search).toString();
        window.location.href = `/feedback/export?${params}`;
    }

    // Show notification
    function showNotification(message, type) {
        const notification = $(`
            <div class="notification ${type}">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                ${message}
            </div>
        `);
        
        $('body').append(notification);
        
        notification.css({
            'position': 'fixed',
            'top': '20px',
            'right': '20px',
            'background': type === 'success' ? '#10b981' : '#ef4444',
            'color': 'white',
            'padding': '1rem 2rem',
            'border-radius': '10px',
            'box-shadow': '0 5px 20px rgba(0,0,0,0.3)',
            'z-index': '9999',
            'display': 'flex',
            'align-items': 'center',
            'gap': '0.75rem',
            'animation': 'slideInRight 0.3s ease'
        });
        
        setTimeout(() => {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Add SweetAlert if not included
    if (typeof Swal === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(script);
    }
</script>
@endpush
