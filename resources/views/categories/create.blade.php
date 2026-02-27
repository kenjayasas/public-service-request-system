@extends('layouts.app')

@section('page-title', 'Create Category')
@section('breadcrumb', 'Categories / Create')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex align-items-center">
                    <div class="header-icon me-3">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div>
                        <h2 class="page-title">Create New Category</h2>
                        <p class="page-subtitle">Add a new service category for citizens to submit requests</p>
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
                            <h5 class="mb-0">Category Information</h5>
                            <small class="text-secondary">Fill in the details below</small>
                        </div>
                    </div>
                </div>

                <div class="form-card-body">
                    <form method="POST" action="{{ route('categories.store') }}" id="createCategoryForm">
                        @csrf

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
                                       value="{{ old('name') }}" 
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
                                Choose a descriptive name for the category
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
                                          placeholder="Describe what this category is for...">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="d-flex justify-content-between mt-2">
                                <small class="form-text text-secondary">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Provide a clear description to help citizens understand
                                </small>
                                <small class="character-count" id="charCount">
                                    <span id="currentChars">0</span>/500
                                </small>
                            </div>
                        </div>

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
                                        <h6 id="preview-name">Category Name</h6>
                                        <p id="preview-description">Category description will appear here</p>
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
                                Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="help-card mt-4">
                <div class="help-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="help-content">
                    <h6><i class="fas fa-star me-2" style="color: var(--orange-primary);"></i>Tips for creating categories:</h6>
                    <ul class="help-list">
                        <li><i class="fas fa-check-circle me-2"></i>Use clear and descriptive names</li>
                        <li><i class="fas fa-check-circle me-2"></i>Keep descriptions concise but informative</li>
                        <li><i class="fas fa-check-circle me-2"></i>Avoid duplicate or overlapping categories</li>
                        <li><i class="fas fa-check-circle me-2"></i>Consider future scalability</li>
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

    .header-icon {
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
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 0;
    }

    /* Form Card */
    .form-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .form-card-header {
        padding: 1.25rem 1.5rem;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-dark);
    }

    .card-icon {
        width: 40px;
        height: 40px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: var(--orange-primary);
        font-size: 1.2rem;
    }

    .form-card-body {
        padding: 2rem;
    }

    /* Form Elements */
    .form-group {
        position: relative;
    }

    .form-label {
        color: var(--text-primary) !important;
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
        color: #9ca3af;
        font-size: 0.85rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }

    /* Error Message */
    .error-message {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid #ef4444;
        color: #f87171;
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
        color: var(--orange-primary);
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
        color: var(--text-primary) !important;
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
    }

    .btn-cancel:hover {
        border-color: #ef4444;
        color: #ef4444 !important;
        background: rgba(239, 68, 68, 0.1);
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
    }

    .help-icon {
        width: 50px;
        height: 50px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange-primary);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .help-content h6 {
        color: var(--text-primary) !important;
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
    }

    .help-list li i {
        color: #10b981;
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

    /* Text visibility fixes */
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
    .character-count {
        color: #ffffff !important;
    }

    .form-control::placeholder {
        color: #9ca3af !important;
        opacity: 1 !important;
    }

    .form-text,
    .help-list li,
    .character-count {
        color: #9ca3af !important;
    }

    .btn-cancel {
        color: #9ca3af !important;
    }

    .btn-submit {
        color: white !important;
    }

    /* Ensure all text inputs have proper contrast */
    input, textarea, select {
        color: #ffffff !important;
        background-color: #2d3748 !important;
    }

    input:focus, textarea:focus, select:focus {
        color: #ffffff !important;
        background-color: #1e2329 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Live preview
    $('#name').on('input', function() {
        const name = $(this).val() || 'Category Name';
        $('#preview-name').text(name);
    });

    $('#description').on('input', function() {
        const description = $(this).val() || 'Category description will appear here';
        $('#preview-description').text(description);
        updateCharCount();
    });

    // Character counter
    function updateCharCount() {
        const length = $('#description').val().length;
        $('#currentChars').text(length);
        
        if (length > 450) {
            $('#currentChars').css('color', '#f59e0b');
        } else if (length > 490) {
            $('#currentChars').css('color', '#ef4444');
        } else {
            $('#currentChars').css('color', '#9ca3af');
        }
    }

    // Form submission with loading state
    $('#createCategoryForm').on('submit', function() {
        const btn = $('#submitBtn');
        btn.addClass('loading');
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Creating...');
    });

    // Character counter for description
    $('#description').on('input', function() {
        const length = $(this).val().length;
        const maxLength = 500;
        
        if (length > maxLength) {
            $(this).val($(this).val().substring(0, maxLength));
        }
        updateCharCount();
    });

    // Auto-focus on name field
    $(document).ready(function() {
        $('#name').focus();
        updateCharCount();
    });

    // Warn before leaving with unsaved changes
    let formChanged = false;
    $('#name, #description').on('input', function() {
        formChanged = true;
    });

    $('#createCategoryForm').on('submit', function() {
        formChanged = false;
    });

    $(window).on('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

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
</script>
@endpush