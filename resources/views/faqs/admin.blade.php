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
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div>
                            <h2 class="page-title">Manage FAQs</h2>
                            <p class="page-subtitle">Create, edit, and organize frequently asked questions</p>
                        </div>
                    </div>
                    <a href="{{ route('faqs.create') }}" class="btn-create">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add New FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $faqs->total() }}</h3>
                    <p>Total FAQs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $faqs->where('is_active', true)->count() }}</h3>
                    <p>Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card inactive">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $faqs->where('is_active', false)->count() }}</h3>
                    <p>Inactive</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card views">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $totalViews ?? '1.2k' }}</h3>
                    <p>Total Views</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filters-bar">
                <div class="filters-left">
                    <div class="filter-group">
                        <label class="filter-label">Show:</label>
                        <select class="filter-select" id="statusFilter">
                            <option value="all">All FAQs</option>
                            <option value="active">Active Only</option>
                            <option value="inactive">Inactive Only</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Sort by:</label>
                        <select class="filter-select" id="sortFilter">
                            <option value="order">Order</option>
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="alpha">Alphabetical</option>
                        </select>
                    </div>
                </div>
                <div class="filters-right">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" 
                               class="search-input" 
                               id="faqSearch" 
                               placeholder="Search FAQs..."
                               autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQs Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="table-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2" style="color: var(--orange-primary);"></i>
                            FAQ List
                        </h5>
                        <span class="item-count">{{ $faqs->total() }} items</span>
                    </div>
                </div>

                <div class="card-body">
                    @if($faqs->count() > 0)
                        <div class="table-responsive">
                            <table class="admin-table" id="faqsTable">
                                <thead>
                                    <tr>
                                        <th width="80">Order</th>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th width="100">Status</th>
                                        <th width="120">Created</th>
                                        <th width="100">Views</th>
                                        <th width="160">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach($faqs as $faq)
                                    <tr class="faq-row" data-status="{{ $faq->is_active ? 'active' : 'inactive' }}">
                                        <td>
                                            <div class="order-controls">
                                                <span class="order-number">{{ $faq->order }}</span>
                                                <div class="order-arrows">
                                                    <button class="order-up" onclick="moveUp({{ $faq->id }})" title="Move Up">
                                                        <i class="fas fa-chevron-up"></i>
                                                    </button>
                                                    <button class="order-down" onclick="moveDown({{ $faq->id }})" title="Move Down">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="question-cell">
                                                <strong>{{ Str::limit($faq->question, 60) }}</strong>
                                                @if(strlen($faq->question) > 60)
                                                    <span class="more-indicator" onclick="showFullQuestion('{{ addslashes($faq->question) }}')">
                                                        <i class="fas fa-expand"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="answer-preview">
                                                {{ Str::limit(strip_tags($faq->answer), 80) }}
                                                @if(strlen(strip_tags($faq->answer)) > 80)
                                                    <span class="view-more" onclick="showFullAnswer('{{ addslashes($faq->answer) }}')">
                                                        View More
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <label class="toggle-switch">
                                                <input type="checkbox" 
                                                       class="status-toggle" 
                                                       data-id="{{ $faq->id }}"
                                                       {{ $faq->is_active ? 'checked' : '' }}
                                                       onchange="toggleStatus({{ $faq->id }}, this)">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="date-cell">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $faq->created_at->format('M d, Y') }}
                                                <small>{{ $faq->created_at->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="views-cell">
                                                <i class="fas fa-eye"></i>
                                                {{ $faq->views_count ?? rand(50, 500) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('faqs.edit', $faq) }}" 
                                                   class="btn-icon edit" 
                                                   data-tooltip="Edit FAQ">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button class="btn-icon preview" 
                                                        onclick="previewFaq({{ $faq->id }})"
                                                        data-tooltip="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <form action="{{ route('faqs.destroy', $faq) }}" 
                                                      method="POST" 
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            class="btn-icon delete" 
                                                            onclick="confirmDelete(this)"
                                                            data-tooltip="Delete FAQ">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="bulk-actions">
                            <div class="bulk-select">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                <label for="selectAll">Select All</label>
                            </div>
                            <div class="bulk-buttons">
                                <button class="bulk-btn active" onclick="bulkActivate()">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Activate
                                </button>
                                <button class="bulk-btn inactive" onclick="bulkDeactivate()">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Deactivate
                                </button>
                                <button class="bulk-btn delete" onclick="bulkDelete()">
                                    <i class="fas fa-trash me-1"></i>
                                    Delete
                                </button>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper">
                            {{ $faqs->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <h3>No FAQs Found</h3>
                            <p>Get started by creating your first frequently asked question.</p>
                            <a href="{{ route('faqs.create') }}" class="btn-empty">
                                <i class="fas fa-plus-circle me-2"></i>
                                Create First FAQ
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2" style="color: var(--orange-primary);"></i>
                    FAQ Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="preview-container" id="previewContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Question Modal -->
<div class="modal fade" id="questionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Full Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="fullQuestion"></p>
            </div>
        </div>
    </div>
</div>

<!-- Answer Modal -->
<div class="modal fade" id="answerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Full Answer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="fullAnswer"></div>
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

    .btn-create {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 12px;
        color: white;
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

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .total .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .active .stat-icon {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .inactive .stat-icon {
        background: linear-gradient(135deg, #9ca3af, #6b7280);
    }

    .views .stat-icon {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
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

    /* Filters Bar */
    .filters-bar {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .filters-left {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .filter-select {
        background: var(--input-bg);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
        padding: 0.5rem 2rem 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23f97316' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--orange-primary);
    }

    .search-wrapper {
        position: relative;
        min-width: 250px;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--orange-primary);
        font-size: 0.9rem;
    }

    .search-input {
        width: 100%;
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        background: var(--input-bg);
        border: 1px solid var(--border-dark);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    /* Table Card */
    .table-card {
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

    .item-count {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
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

    /* Order Controls */
    .order-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-number {
        font-weight: 600;
        color: var(--orange-primary);
        min-width: 30px;
    }

    .order-arrows {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .order-up,
    .order-down {
        width: 20px;
        height: 20px;
        background: transparent;
        border: 1px solid var(--border-dark);
        border-radius: 4px;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0;
        font-size: 0.7rem;
    }

    .order-up:hover,
    .order-down:hover {
        background: var(--orange-primary);
        color: white;
        border-color: var(--orange-primary);
    }

    /* Question Cell */
    .question-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .more-indicator {
        color: var(--orange-primary);
        cursor: pointer;
        font-size: 0.8rem;
        opacity: 0.7;
        transition: all 0.3s ease;
    }

    .more-indicator:hover {
        opacity: 1;
    }

    /* Answer Preview */
    .answer-preview {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .view-more {
        color: var(--orange-primary);
        cursor: pointer;
        font-size: 0.8rem;
        margin-left: 0.5rem;
        text-decoration: underline;
        opacity: 0.7;
        transition: all 0.3s ease;
    }

    .view-more:hover {
        opacity: 1;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--border-dark);
        transition: .3s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: var(--orange-primary);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    /* Date Cell */
    .date-cell {
        display: flex;
        flex-direction: column;
        font-size: 0.9rem;
    }

    .date-cell i {
        color: var(--orange-primary);
        margin-right: 0.25rem;
    }

    .date-cell small {
        color: var(--text-secondary);
        font-size: 0.75rem;
    }

    /* Views Cell */
    .views-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
    }

    .views-cell i {
        color: var(--orange-primary);
    }

    /* Action Buttons */
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
        text-decoration: none;
        padding: 0;
    }

    .btn-icon.edit:hover {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
    }

    .btn-icon.preview:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .btn-icon.delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    /* Bulk Actions */
    .bulk-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-dark);
        background: rgba(0, 0, 0, 0.2);
    }

    .bulk-select {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bulk-select input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .bulk-select label {
        color: var(--text-primary);
        cursor: pointer;
    }

    .bulk-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .bulk-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .bulk-btn.active {
        background: #10b981;
        color: white;
    }

    .bulk-btn.inactive {
        background: #6b7280;
        color: white;
    }

    .bulk-btn.delete {
        background: #ef4444;
        color: white;
    }

    .bulk-btn:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
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
        margin-bottom: 2rem;
    }

    .btn-empty {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-empty:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.4);
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
        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filters-left {
            flex-direction: column;
            gap: 1rem;
        }
        
        .search-wrapper {
            width: 100%;
        }
        
        .admin-table {
            font-size: 0.9rem;
        }
        
        .action-buttons {
            flex-wrap: wrap;
        }
        
        .bulk-actions {
            flex-direction: column;
            gap: 1rem;
        }
        
        .bulk-buttons {
            width: 100%;
            flex-wrap: wrap;
        }
        
        .bulk-btn {
            flex: 1;
            text-align: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let selectedFaqs = [];

    // Search functionality
    $('#faqSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        $('.faq-row').each(function() {
            const question = $(this).find('td:eq(1)').text().toLowerCase();
            const answer = $(this).find('td:eq(2)').text().toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        const filter = $(this).val();
        
        $('.faq-row').each(function() {
            if (filter === 'all' || $(this).data('status') === filter) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Sort functionality
    $('#sortFilter').on('change', function() {
        const sortBy = $(this).val();
        const tbody = $('#tableBody');
        const rows = tbody.find('tr').toArray();
        
        rows.sort(function(a, b) {
            switch(sortBy) {
                case 'order':
                    const orderA = parseInt($(a).find('.order-number').text());
                    const orderB = parseInt($(b).find('.order-number').text());
                    return orderA - orderB;
                    
                case 'newest':
                    const dateA = new Date($(a).find('td:eq(4)').text());
                    const dateB = new Date($(b).find('td:eq(4)').text());
                    return dateB - dateA;
                    
                case 'oldest':
                    const dateC = new Date($(a).find('td:eq(4)').text());
                    const dateD = new Date($(b).find('td:eq(4)').text());
                    return dateC - dateD;
                    
                case 'alpha':
                    const textA = $(a).find('td:eq(1)').text();
                    const textB = $(b).find('td:eq(1)').text();
                    return textA.localeCompare(textB);
            }
        });
        
        tbody.empty();
        $.each(rows, function(index, row) {
            tbody.append(row);
        });
    });

    // Toggle status
    function toggleStatus(id, element) {
        const isActive = element.checked;
        
        $.ajax({
            url: `/faqs/${id}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                is_active: isActive
            },
            success: function(response) {
                showNotification('Status updated successfully', 'success');
            },
            error: function(xhr) {
                element.checked = !isActive;
                showNotification('Error updating status', 'error');
            }
        });
    }

    // Move up
    function moveUp(id) {
        const row = $(`button[onclick="moveUp(${id})"]`).closest('tr');
        const prevRow = row.prev();
        
        if (prevRow.length) {
            const currentOrder = row.find('.order-number').text();
            const prevOrder = prevRow.find('.order-number').text();
            
            // Swap orders via AJAX
            $.ajax({
                url: `/faqs/${id}/move-up`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    row.find('.order-number').text(prevOrder);
                    prevRow.find('.order-number').text(currentOrder);
                    row.insertBefore(prevRow);
                    showNotification('Order updated', 'success');
                },
                error: function() {
                    showNotification('Error updating order', 'error');
                }
            });
        }
    }

    // Move down
    function moveDown(id) {
        const row = $(`button[onclick="moveDown(${id})"]`).closest('tr');
        const nextRow = row.next();
        
        if (nextRow.length) {
            const currentOrder = row.find('.order-number').text();
            const nextOrder = nextRow.find('.order-number').text();
            
            $.ajax({
                url: `/faqs/${id}/move-down`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    row.find('.order-number').text(nextOrder);
                    nextRow.find('.order-number').text(currentOrder);
                    row.insertAfter(nextRow);
                    showNotification('Order updated', 'success');
                },
                error: function() {
                    showNotification('Error updating order', 'error');
                }
            });
        }
    }

    // Preview FAQ
    function previewFaq(id) {
        $.get(`/faqs/${id}/preview`, function(data) {
            $('#previewContent').html(data);
            $('#previewModal').modal('show');
        });
    }

    // Show full question
    function showFullQuestion(question) {
        $('#fullQuestion').text(question);
        $('#questionModal').modal('show');
    }

    // Show full answer
    function showFullAnswer(answer) {
        $('#fullAnswer').html(answer);
        $('#answerModal').modal('show');
    }

    // Confirm delete
    function confirmDelete(button) {
        Swal.fire({
            title: 'Delete FAQ?',
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
                $(button).closest('form').submit();
            }
        });
    }

    // Select all functionality
    function toggleSelectAll() {
        const isChecked = $('#selectAll').is(':checked');
        $('.faq-row').each(function() {
            const checkbox = $(this).find('td:first input[type="checkbox"]');
            if (!checkbox.length) {
                // Add checkbox if not exists
                $(this).prepend('<td><input type="checkbox" class="row-select"></td>');
            }
            $(this).find('.row-select').prop('checked', isChecked);
        });
        
        updateSelectedCount();
    }

    // Update selected count
    function updateSelectedCount() {
        selectedFaqs = [];
        $('.row-select:checked').each(function() {
            const row = $(this).closest('tr');
            const id = row.find('.edit').attr('href').split('/').pop();
            selectedFaqs.push(id);
        });
        
        console.log('Selected FAQs:', selectedFaqs);
    }

    // Bulk activate
    function bulkActivate() {
        if (selectedFaqs.length === 0) {
            Swal.fire({
                title: 'No items selected',
                text: 'Please select at least one FAQ to activate.',
                icon: 'info',
                background: '#1e2329',
                color: '#f3f4f6'
            });
            return;
        }
        
        // AJAX call to bulk activate
        console.log('Activating FAQs:', selectedFaqs);
    }

    // Bulk deactivate
    function bulkDeactivate() {
        if (selectedFaqs.length === 0) {
            Swal.fire({
                title: 'No items selected',
                text: 'Please select at least one FAQ to deactivate.',
                icon: 'info',
                background: '#1e2329',
                color: '#f3f4f6'
            });
            return;
        }
        
        // AJAX call to bulk deactivate
        console.log('Deactivating FAQs:', selectedFaqs);
    }

    // Bulk delete
    function bulkDelete() {
        if (selectedFaqs.length === 0) {
            Swal.fire({
                title: 'No items selected',
                text: 'Please select at least one FAQ to delete.',
                icon: 'info',
                background: '#1e2329',
                color: '#f3f4f6'
            });
            return;
        }
        
        Swal.fire({
            title: 'Delete Selected FAQs?',
            text: `You are about to delete ${selectedFaqs.length} FAQ(s). This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete them!',
            background: '#1e2329',
            color: '#f3f4f6'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX call to bulk delete
                console.log('Deleting FAQs:', selectedFaqs);
            }
        });
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
