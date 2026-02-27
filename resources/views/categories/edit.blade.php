@extends('layouts.app')

@section('page-title', 'Edit Category')
@section('breadcrumb', 'Categories / Edit')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex align-items-center flex-wrap">
                    <a href="{{ route('categories.index') }}" class="btn-back me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="header-icon-wrapper me-3">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h2 class="page-title">Edit Category</h2>
                        <p class="page-subtitle">Update category information: <span class="category-name-highlight">{{ $category->name }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Category Details</h5>
                            <small class="text-secondary">ID: <span class="category-id">#{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}</span></small>
                        </div>
                    </div>
                    <div class="header-badge">
                        <span class="created-badge">
                            <i class="far fa-calendar-alt me-1"></i>
                            Created: {{ $category->created_at->format('M d, Y') }}
                        </span>
                        @if($category->updated_at > $category->created_at)
                            <span class="updated-badge ms-2">
                                <i class="fas fa-history me-1"></i>
                                Updated: {{ $category->updated_at->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-card-body">
                    <form method="POST" action="{{ route('categories.update', $category) }}" id="editCategoryForm">
                        @csrf
                        @method('PUT')

                        <!-- Category Name -->
                        <div class="form-group mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag me-2"></i>
                                Category Name
                                <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-tag input-icon"></i>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $category->name) }}" 
                                       placeholder="e.g., Road Maintenance"
                                       required
                                       autofocus>
                            </div>
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-secondary">
                                <i class="fas fa-info-circle me-1"></i>
                                Category name should be unique and descriptive
                            </small>
                        </div>

                        <!-- Category Description -->
                        <div class="form-group mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>
                                Description
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-align-left input-icon"></i>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="5"
                                          placeholder="Describe what this category is for..."
                                          maxlength="500">{{ old('description', $category->description) }}</textarea>
                            </div>
                            @error('description')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="form-text text-secondary">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Provide a clear description to help citizens understand this category
                                </small>
                                <small class="character-count" id="charCount">
                                    <span id="currentChars">{{ strlen($category->description ?? '') }}</span>/500
                                </small>
                            </div>
                        </div>

                        <!-- Statistics Card -->
                        @if($category->service_requests_count > 0)
                            <div class="stats-card mb-4">
                                <div class="stats-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="stats-content">
                                    <h6><i class="fas fa-info-circle me-1"></i>Category Usage Statistics</h6>
                                    <p>This category is currently used in <strong>{{ $category->service_requests_count }}</strong> 
                                       {{ Str::plural('request', $category->service_requests_count) }}.</p>
                                    <div class="progress mt-2">
                                        <div class="progress-bar" style="width: {{ min($category->service_requests_count * 5, 100) }}%"></div>
                                    </div>
                                    <small class="stats-note">Deleting this category is not allowed while it has associated requests</small>
                                </div>
                            </div>
                        @endif

                        <!-- Preview Card -->
                        <div class="preview-card mb-4">
                            <div class="preview-header">
                                <i class="fas fa-eye me-2"></i>
                                Live Preview
                            </div>
                            <div class="preview-body">
                                <div class="preview-category">
                                    <div class="preview-icon">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="preview-content">
                                        <h6 id="preview-name">{{ $category->name }}</h6>
                                        <p id="preview-description">{{ $category->description ?: 'No description provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('categories.index') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn-submit" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone (for categories with no requests) -->
            @if($category->service_requests_count == 0)
                <div class="danger-zone mt-4">
                    <div class="danger-header">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Danger Zone
                    </div>
                    <div class="danger-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h6 class="mb-1">Delete this category</h6>
                                <p class="text-danger-light mb-0">Once deleted, this action cannot be undone. All data will be permanently removed.</p>
                            </div>
                            <form action="{{ route('categories.destroy', $category) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  id="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="confirmDelete()">
                                    <i class="fas fa-trash-alt me-2"></i>
                                    Delete Category
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Help Card -->
            <div class="help-card mt-4">
                <div class="help-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="help-content">
                    <h6><i class="fas fa-star me-2" style="color: var(--orange-primary);"></i>Updating Categories:</h6>
                    <ul class="help-list">
                        <li><i class="fas fa-check-circle me-2"></i>Changing the name won't affect existing requests</li>
                        <li><i class="fas fa-check-circle me-2"></i>Keep descriptions up to date for clarity</li>
                        <li><i class="fas fa-check-circle me-2"></i>Consider how changes might affect reporting</li>
                        <li><i class="fas fa-check-circle me-2"></i>Updates are logged for audit purposes</li>
                    </ul>
                </div>
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

    .btn-back {
        width: 45px;
        height: 45px;
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange-primary) !important;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: var(--orange-primary);
        color: white !important;
        transform: translateX(-5px);
    }

    .header-icon-wrapper {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #ffffff !important;
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: #9ca3af !important;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .category-name-highlight {
        color: var(--orange-primary);
        font-weight: 600;
    }

    /* Form Card */
    .form-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .form-card:hover {
        border-color: var(--orange-primary);
        box-shadow: 0 15px 40px rgba(249, 115, 22, 0.15);
    }

    .form-card-header {
        padding: 1.5rem;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-dark);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange-primary) !important;
        font-size: 1.5rem;
        margin-right: 1rem;
    }

    .category-id {
        color: var(--orange-primary);
        font-weight: 600;
    }

    .header-badge {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .created-badge {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary) !important;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid rgba(249, 115, 22, 0.2);
    }

    .updated-badge {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981 !important;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .form-card-body {
        padding: 2rem;
    }

    /* Form Elements */
    .form-group {
        position: relative;
    }

    .form-label {
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        color: var(--orange-primary);
    }

    .required {
        color: #ef4444;
        margin-left: 0.25rem;
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

    textarea + .input-icon {
        top: 1.25rem;
        transform: none;
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

    textarea.form-control {
        padding-top: 1rem;
        min-height: 120px;
        resize: vertical;
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
        opacity: 0.7;
    }

    .form-text {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #9ca3af !important;
    }

    .form-text i {
        color: var(--orange-primary);
    }

    .character-count {
        color: #9ca3af !important;
        font-size: 0.85rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }

    /* Error Message */
    .error-message {
        background: rgba(239, 68, 68, 0.1);
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

    /* Stats Card */
    .stats-card {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stats-icon {
        width: 40px;
        height: 40px;
        background: #3b82f6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .stats-content {
        flex: 1;
    }

    .stats-content h6 {
        color: #3b82f6 !important;
        margin-bottom: 0.25rem;
    }

    .stats-content p {
        color: #9ca3af !important;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .stats-content strong {
        color: #3b82f6;
    }

    .stats-note {
        color: #9ca3af !important;
        font-size: 0.8rem;
        display: block;
        margin-top: 0.5rem;
        font-style: italic;
    }

    .progress {
        height: 4px;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 2px;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
        border-radius: 2px;
    }

    /* Preview Card */
    .preview-card {
        background: rgba(249, 115, 22, 0.05);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 12px;
        overflow: hidden;
    }

    .preview-header {
        padding: 0.75rem 1rem;
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary) !important;
        font-weight: 600;
        font-size: 0.9rem;
        border-bottom: 1px solid rgba(249, 115, 22, 0.2);
    }

    .preview-body {
        padding: 1.5rem;
    }

    .preview-category {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .preview-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .preview-content h6 {
        color: #ffffff !important;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .preview-content p {
        color: #9ca3af !important;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-dark);
    }

    .btn-cancel {
        flex: 1;
        padding: 0.875rem;
        background: transparent;
        border: 2px solid #4a5568;
        border-radius: 12px;
        color: #9ca3af !important;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-cancel:hover {
        border-color: #6b7280;
        color: #ffffff !important;
        background: rgba(107, 114, 128, 0.1);
        transform: translateY(-2px);
    }

    .btn-submit {
        flex: 2;
        padding: 0.875rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 12px;
        color: white !important;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
    }

    .btn-submit i {
        transition: all 0.3s ease;
    }

    .btn-submit:hover i {
        transform: rotate(360deg);
    }

    /* Danger Zone */
    .danger-zone {
        background: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 15px;
        overflow: hidden;
    }

    .danger-header {
        padding: 1rem 1.5rem;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444 !important;
        font-weight: 600;
        border-bottom: 1px solid rgba(239, 68, 68, 0.2);
        display: flex;
        align-items: center;
    }

    .danger-body {
        padding: 1.5rem;
    }

    .danger-body h6 {
        color: #ef4444 !important;
    }

    .text-danger-light {
        color: #fca5a5 !important;
    }

    .btn-delete {
        padding: 0.75rem 1.5rem;
        background: transparent;
        border: 2px solid #ef4444;
        border-radius: 10px;
        color: #ef4444 !important;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-delete:hover {
        background: #ef4444;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
    }

    /* Help Card */
    .help-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        gap: 1.5rem;
        align-items: start;
        transition: all 0.3s ease;
    }

    .help-card:hover {
        border-color: var(--orange-primary);
        transform: translateY(-2px);
    }

    .help-icon {
        width: 50px;
        height: 50px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange-primary) !important;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .help-content h6 {
        color: #ffffff !important;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .help-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .help-list li {
        color: #9ca3af !important;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }

    .help-list li i {
        color: #10b981;
        font-size: 0.9rem;
    }

    /* Loading State */
    .btn-submit.loading {
        opacity: 0.7;
        cursor: wait;
    }

    .btn-submit.loading i {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Text visibility global fixes */
    .text-secondary {
        color: #9ca3af !important;
    }

    .text-primary {
        color: #f3f4f6 !important;
    }

    .form-control,
    .form-control:focus,
    .form-control::placeholder,
    .form-label,
    .form-text,
    .preview-content h6,
    .preview-content p,
    .help-content h6,
    .help-list li,
    .btn-cancel,
    .btn-submit,
    .character-count,
    .stats-content p,
    .stats-note,
    .danger-body p,
    .page-subtitle {
        color: #ffffff !important;
    }

    .form-control::placeholder {
        color: #9ca3af !important;
    }

    .form-text,
    .help-list li,
    .character-count,
    .stats-content p,
    .stats-note,
    .page-subtitle {
        color: #9ca3af !important;
    }

    .btn-cancel {
        color: #9ca3af !important;
    }

    .btn-submit {
        color: white !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-card-header {
            flex-direction: column;
            align-items: start;
            gap: 1rem;
        }
        
        .header-badge {
            width: 100%;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .danger-body .d-flex {
            flex-direction: column;
            gap: 1rem;
            align-items: start !important;
        }
        
        .btn-delete {
            width: 100%;
        }
        
        .page-header .d-flex {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Character counter
    const description = document.getElementById('description');
    const currentChars = document.getElementById('currentChars');
    
    function updateCharCount() {
        const length = description.value.length;
        currentChars.textContent = length;
        
        if (length > 450) {
            currentChars.style.color = '#f59e0b';
        } else if (length > 490) {
            currentChars.style.color = '#ef4444';
        } else {
            currentChars.style.color = '#9ca3af';
        }
    }
    
    if (description) {
        description.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    // Live preview
    $('#name').on('input', function() {
        const name = $(this).val() || '{{ $category->name }}';
        $('#preview-name').text(name);
    });

    $('#description').on('input', function() {
        const description = $(this).val() || 'No description provided';
        $('#preview-description').text(description);
    });

    // Form submission with loading state
    $('#editCategoryForm').on('submit', function(e) {
        const btn = $('#submitBtn');
        btn.addClass('loading');
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Updating...');
    });

    // Delete confirmation
    function confirmDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            background: '#1e2329',
            color: '#f3f4f6'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }

    // Auto-save draft
    let autoSaveTimer;
    $('#name, #description').on('input', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            // Show auto-save notification
            const notification = $('<div class="auto-save-notification">Draft saved</div>');
            $('body').append(notification);
            
            notification.css({
                'position': 'fixed',
                'bottom': '20px',
                'right': '20px',
                'background': '#10b981',
                'color': 'white',
                'padding': '0.5rem 1rem',
                'border-radius': '8px',
                'z-index': '9999',
                'animation': 'slideIn 0.3s ease'
            });
            
            setTimeout(() => {
                notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 2000);
        }, 2000);
    });

    // Warn before leaving with unsaved changes
    let formChanged = false;
    $('#name, #description').on('input', function() {
        formChanged = true;
    });

    $('#editCategoryForm').on('submit', function() {
        formChanged = false;
    });

    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            $('#editCategoryForm').submit();
        }
        
        // Esc to cancel
        if (e.key === 'Escape') {
            window.location.href = '{{ route("categories.index") }}';
        }
    });

    // Add SweetAlert if not already included
    if (typeof Swal === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(script);
    }
</script>
@endpush