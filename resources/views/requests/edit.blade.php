@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex align-items-center flex-wrap">
                    <a href="{{ route('requests.show', $serviceRequest) }}" class="btn-back me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <h2 class="page-title">
                                Edit Request
                                <span class="request-id-badge">#{{ str_pad($serviceRequest->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </h2>
                            <p class="page-subtitle">
                                Update the details of this service request
                            </p>
                        </div>
                    </div>
                    <div class="header-status ms-auto">
                        @php
                            $statusColors = [
                                'pending' => ['bg' => '#f59e0b20', 'text' => '#f59e0b'],
                                'in_progress' => ['bg' => '#3b82f620', 'text' => '#3b82f6'],
                                'completed' => ['bg' => '#10b98120', 'text' => '#10b981'],
                                'rejected' => ['bg' => '#ef444420', 'text' => '#ef4444']
                            ];
                            $status = $statusColors[$serviceRequest->status];
                        @endphp
                        <span class="current-status" style="background: {{ $status['bg'] }}; color: {{ $status['text'] }};">
                            <i class="fas fa-circle me-1"></i>
                            Current: {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Edit Form Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="fas fa-pen"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Edit Request Information</h5>
                            <small class="text-secondary">Make changes to the request details below</small>
                        </div>
                    </div>
                    <div class="header-badge">
                        <i class="fas fa-info-circle me-1"></i>
                        Fields marked with * are required
                    </div>
                </div>

                <div class="form-card-body">
                    <form method="POST" action="{{ route('requests.update', $serviceRequest) }}" id="editRequestForm">
                        @csrf
                        @method('PUT')

                        <!-- Admin-only Fields -->
                        @if(auth()->user()->isAdmin())
                        <div class="admin-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-shield-alt me-2"></i>
                                Administrative Settings
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">
                                        <i class="fas fa-tag me-2"></i>
                                        Category
                                        <span class="required">*</span>
                                    </label>
                                    <div class="select-wrapper">
                                        <i class="fas fa-chevron-down select-icon"></i>
                                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                                id="category_id" 
                                                name="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $serviceRequest->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="assigned_staff_id" class="form-label">
                                        <i class="fas fa-user-tie me-2"></i>
                                        Assign Staff
                                    </label>
                                    <div class="select-wrapper">
                                        <i class="fas fa-chevron-down select-icon"></i>
                                        <select class="form-select @error('assigned_staff_id') is-invalid @enderror" 
                                                id="assigned_staff_id" 
                                                name="assigned_staff_id">
                                            <option value="">Unassigned</option>
                                            @foreach($staff as $staffMember)
                                                <option value="{{ $staffMember->id }}" 
                                                    {{ old('assigned_staff_id', $serviceRequest->assigned_staff_id) == $staffMember->id ? 'selected' : '' }}>
                                                    {{ $staffMember->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('assigned_staff_id')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Status Update -->
                        <div class="status-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-sync-alt me-2"></i>
                                Request Status
                            </h6>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    <i class="fas fa-flag me-2"></i>
                                    Status
                                    <span class="required">*</span>
                                </label>
                                <div class="status-options">
                                    @php
                                        $statuses = [
                                            'pending' => ['label' => 'Pending', 'color' => '#f59e0b', 'icon' => 'fa-clock'],
                                            'in_progress' => ['label' => 'In Progress', 'color' => '#3b82f6', 'icon' => 'fa-spinner'],
                                            'completed' => ['label' => 'Completed', 'color' => '#10b981', 'icon' => 'fa-check-circle'],
                                            'rejected' => ['label' => 'Rejected', 'color' => '#ef4444', 'icon' => 'fa-times-circle']
                                        ];
                                    @endphp
                                    
                                    <div class="status-grid">
                                        @foreach($statuses as $value => $status)
                                            <div class="status-option">
                                                <input type="radio" 
                                                       name="status" 
                                                       id="status_{{ $value }}" 
                                                       value="{{ $value }}"
                                                       {{ old('status', $serviceRequest->status) == $value ? 'checked' : '' }}
                                                       required>
                                                <label for="status_{{ $value }}" 
                                                       class="status-label"
                                                       style="border-color: {{ $status['color'] }}20;
                                                              background: {{ old('status', $serviceRequest->status) == $value ? $status['color'] : 'transparent' }};
                                                              color: {{ old('status', $serviceRequest->status) == $value ? 'white' : $status['color'] }};">
                                                    <i class="fas {{ $status['icon'] }} me-2"></i>
                                                    {{ $status['label'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('status')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Request Summary -->
                        <div class="summary-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Request Summary
                            </h6>
                            
                            <div class="summary-box">
                                <div class="summary-item">
                                    <span class="summary-label">Title:</span>
                                    <span class="summary-value">{{ $serviceRequest->title }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Description:</span>
                                    <span class="summary-value">{{ Str::limit($serviceRequest->description, 100) }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Location:</span>
                                    <span class="summary-value">{{ $serviceRequest->location }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Submitted by:</span>
                                    <span class="summary-value">{{ $serviceRequest->user->name }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Update Notes (Optional) -->
                        <div class="notes-section mb-4">
                            <label for="update_notes" class="form-label">
                                <i class="fas fa-sticky-note me-2"></i>
                                Update Notes (Optional)
                            </label>
                            <textarea class="form-control" 
                                      id="update_notes" 
                                      name="update_notes" 
                                      rows="3"
                                      placeholder="Add any notes about this update..."></textarea>
                            <small class="form-text text-secondary">
                                These notes will be visible in the request timeline
                            </small>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('requests.show', $serviceRequest) }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn-submit" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                Update Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="warning-card mt-4">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="warning-content">
                    <h6>Important Notes:</h6>
                    <ul>
                        <li><i class="fas fa-check-circle me-2"></i>Changing the status will notify the citizen via email</li>
                        <li><i class="fas fa-check-circle me-2"></i>Staff assignment changes are logged in the timeline</li>
                        <li><i class="fas fa-check-circle me-2"></i>All updates are recorded for audit purposes</li>
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
        color: var(--orange-primary);
        font-size: 1.2rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: var(--orange-primary);
        color: white;
        transform: translateX(-5px);
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 2rem;
        color: white;
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .request-id-badge {
        font-size: 1rem;
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        padding: 0.25rem 1rem;
        border-radius: 20px;
        border: 1px solid rgba(249, 115, 22, 0.2);
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 0;
    }

    .current-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
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
        margin-right: 1rem;
        color: var(--orange-primary);
        font-size: 1.5rem;
    }

    .header-badge {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        border: 1px solid rgba(249, 115, 22, 0.2);
    }

    .form-card-body {
        padding: 2rem;
    }

    /* Section Titles */
    .section-title {
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-dark);
    }

    .section-title i {
        color: var(--orange-primary);
    }

    /* Admin Section */
    .admin-section {
        background: rgba(249, 115, 22, 0.05);
        border: 1px solid rgba(249, 115, 22, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
    }

    /* Form Elements */
    .form-label {
        color: var(--text-primary);
        font-weight: 500;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
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

    .select-wrapper {
        position: relative;
    }

    .select-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--orange-primary);
        pointer-events: none;
        z-index: 1;
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        background-color: var(--input-bg);
        border: 2px solid var(--input-border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 1rem;
        appearance: none;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
    }

    .form-select option {
        background-color: var(--dark-card);
        color: var(--text-primary);
    }

    /* Status Options */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .status-option {
        position: relative;
    }

    .status-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .status-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1rem;
        border: 2px solid;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        font-weight: 500;
    }

    .status-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .status-option input[type="radio"]:checked + .status-label {
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    /* Summary Box */
    .summary-box {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-dark);
        border-radius: 12px;
        padding: 1rem;
    }

    .summary-item {
        display: flex;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-dark);
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        width: 120px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .summary-value {
        flex: 1;
        color: var(--text-primary);
    }

    /* Notes Section */
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        background-color: var(--input-bg);
        border: 2px solid var(--input-border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
        resize: vertical;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
        background-color: var(--dark-card);
    }

    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }

    .form-text {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.85rem;
    }

    /* Error Message */
    .error-message {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid #ef4444;
        color: #f87171;
        padding: 0.5rem 1rem;
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
        padding: 0.875rem 1.5rem;
        background: transparent;
        border: 2px solid var(--border-dark);
        border-radius: 12px;
        color: var(--text-secondary);
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        border-color: #6b7280;
        color: var(--text-primary);
        background: rgba(107, 114, 128, 0.1);
        transform: translateY(-2px);
    }

    .btn-submit {
        flex: 2;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 12px;
        color: white;
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

    /* Warning Card */
    .warning-card {
        background: rgba(245, 158, 11, 0.05);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        gap: 1.5rem;
        align-items: start;
    }

    .warning-icon {
        width: 50px;
        height: 50px;
        background: rgba(245, 158, 11, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f59e0b;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .warning-content h6 {
        color: #f59e0b;
        margin-bottom: 0.5rem;
    }

    .warning-content ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .warning-content li {
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
    }

    .warning-content li i {
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

    /* Responsive */
    @media (max-width: 768px) {
        .page-header .d-flex {
            flex-direction: column;
            align-items: start;
            gap: 1rem;
        }
        
        .header-status {
            margin-left: 0 !important;
            width: 100%;
        }
        
        .current-status {
            width: 100%;
            justify-content: center;
        }
        
        .form-card-header {
            flex-direction: column;
            align-items: start;
        }
        
        .status-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .summary-item {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .summary-label {
            width: auto;
        }
        
        .warning-card {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }

    /* Text visibility enhancements */
    .form-select,
    .form-control,
    .status-label,
    .summary-value,
    .section-title,
    .form-label {
        color: var(--text-primary) !important;
    }

    .form-select option {
        background-color: var(--dark-card);
        color: var(--text-primary);
    }

    .form-select optgroup {
        background-color: var(--dark-card);
        color: var(--text-primary);
    }

    /* Improve text visibility when typing */
    .form-control:focus,
    .form-select:focus {
        color: var(--text-primary) !important;
        background-color: var(--dark-card);
    }

    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }

    /* Ensure selected option text is visible */
    .form-select option:checked,
    .form-select option:hover {
        background-color: var(--orange-primary);
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
    // Form submission with loading state
    $('#editRequestForm').on('submit', function(e) {
        const btn = $('#submitBtn');
        btn.addClass('loading');
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Updating...');
    });

    // Warn before leaving with unsaved changes
    let formChanged = false;
    const formInputs = document.querySelectorAll('#editRequestForm select, #editRequestForm textarea');
    
    formInputs.forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    $('#update_notes').on('input', function() {
        formChanged = true;
    });

    $('#editRequestForm').on('submit', function() {
        formChanged = false;
    });

    $(window).on('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Auto-resize textarea
    $('#update_notes').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Preview status change
    $('input[name="status"]').on('change', function() {
        const selectedValue = $(this).val();
        const statusColors = {
            'pending': '#f59e0b',
            'in_progress': '#3b82f6',
            'completed': '#10b981',
            'rejected': '#ef4444'
        };
        
        // Update all status labels
        $('.status-label').each(function() {
            const input = $(this).prev('input[type="radio"]');
            if (input.val() === selectedValue) {
                $(this).css({
                    'background': statusColors[selectedValue],
                    'color': 'white'
                });
            } else {
                $(this).css({
                    'background': 'transparent',
                    'color': statusColors[input.val()]
                });
            }
        });
    });

    // Character counter for notes
    $('#update_notes').on('input', function() {
        const length = $(this).val().length;
        if (length > 0) {
            if (!$('.notes-counter').length) {
                $(this).after('<small class="notes-counter text-secondary ms-2"></small>');
            }
            $('.notes-counter').text(length + ' characters');
        } else {
            $('.notes-counter').remove();
        }
    });

    // Initialize tooltips
    $(document).ready(function() {
        // Set initial textarea height
        $('#update_notes').css('height', 'auto');
        $('#update_notes').css('height', $('#update_notes')[0].scrollHeight + 'px');
    });
</script>
@endpush
