@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex align-items-center flex-wrap">
                    <a href="{{ route('requests.index') }}" class="btn-back me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <div class="header-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h2 class="page-title">
                                Request Details
                                <span class="request-id-badge">#{{ str_pad($serviceRequest->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </h2>
                            <p class="page-subtitle">
                                Submitted {{ $serviceRequest->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="header-actions ms-auto">
                        @php
                            $statusColors = [
                                'pending' => ['bg' => '#f59e0b20', 'text' => '#f59e0b', 'icon' => 'fa-clock'],
                                'in_progress' => ['bg' => '#3b82f620', 'text' => '#3b82f6', 'icon' => 'fa-spinner fa-spin'],
                                'completed' => ['bg' => '#10b98120', 'text' => '#10b981', 'icon' => 'fa-check-circle'],
                                'rejected' => ['bg' => '#ef444420', 'text' => '#ef4444', 'icon' => 'fa-times-circle']
                            ];
                            $status = $statusColors[$serviceRequest->status];
                        @endphp
                        <span class="status-badge-large" style="background: {{ $status['bg'] }}; color: {{ $status['text'] }};">
                            <i class="fas {{ $status['icon'] }} me-2"></i>
                            {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content - Left Column -->
        <div class="col-lg-8">
            <!-- Request Details Card -->
            <div class="details-card mb-4">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h5 class="mb-0">Request Information</h5>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="details-grid">
                        <!-- Title -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-heading"></i>
                                Title
                            </div>
                            <div class="detail-value">
                                {{ $serviceRequest->title }}
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-tag"></i>
                                Category
                            </div>
                            <div class="detail-value">
                                <span class="category-badge">
                                    {{ $serviceRequest->category?->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="detail-item full-width">
                            <div class="detail-label">
                                <i class="fas fa-align-left"></i>
                                Description
                            </div>
                            <div class="detail-value description-text">
                                {{ $serviceRequest->description }}
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Location
                            </div>
                            <div class="detail-value">
                                <i class="fas fa-map-pin me-1" style="color: var(--orange-primary);"></i>
                                {{ $serviceRequest->location }}
                            </div>
                        </div>

                        <!-- Image -->
                        @if($serviceRequest->image)
                        <div class="detail-item full-width">
                            <div class="detail-label">
                                <i class="fas fa-image"></i>
                                Attached Image
                            </div>
                            <div class="detail-value">
                                <div class="image-gallery">
                                    <img src="{{ asset('storage/' . $serviceRequest->image) }}" 
                                         alt="Request Image" 
                                         class="request-image"
                                         onclick="openImageModal(this.src)">
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Submitted By -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-user"></i>
                                Submitted By
                            </div>
                            <div class="detail-value">
                                <div class="user-info">
                                    <i class="fas fa-user-circle"></i>
                                    {{ $serviceRequest->user->name }}
                                    <small class="text-secondary d-block">
                                        {{ $serviceRequest->user->email }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Submitted On -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="far fa-calendar-alt"></i>
                                Submitted On
                            </div>
                            <div class="detail-value">
                                {{ $serviceRequest->created_at->format('F d, Y') }}
                                <small class="text-secondary d-block">
                                    {{ $serviceRequest->created_at->format('h:i A') }}
                                </small>
                            </div>
                        </div>

                        <!-- Assigned To -->
                        @if($serviceRequest->assignedStaff)
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-user-tie"></i>
                                Assigned To
                            </div>
                            <div class="detail-value">
                                <div class="staff-info">
                                    <i class="fas fa-user-circle"></i>
                                    {{ $serviceRequest->assignedStaff->name }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Last Updated -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-history"></i>
                                Last Updated
                            </div>
                            <div class="detail-value">
                                {{ $serviceRequest->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Card -->
            <div class="messages-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="card-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h5 class="mb-0">Discussion</h5>
                        </div>
                        <span class="message-count">{{ $serviceRequest->messages->count() }} messages</span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Messages Container -->
                    <div class="messages-container" id="messageContainer">
                        @forelse($serviceRequest->messages as $message)
                            <div class="message-item {{ $message->sender_id == auth()->id() ? 'own-message' : 'other-message' }}" 
                                 data-id="{{ $message->id }}">
                                <div class="message-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="message-sender">{{ $message->sender->name }}</span>
                                        <span class="message-time">{{ $message->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="message-body">
                                        {{ $message->message }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-messages">
                                <i class="fas fa-comments"></i>
                                <h6>No messages yet</h6>
                                <p>Start the conversation by sending a message</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Message Input -->
                    <div class="message-input-container">
                        <form id="messageForm" class="message-form">
                            @csrf
                            <input type="hidden" name="receiver_id" 
                                   value="{{ auth()->user()->isAdmin() || auth()->user()->isStaff() ? $serviceRequest->user_id : ($serviceRequest->assigned_staff_id ?? '') }}">
                            <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                            
                            <div class="input-group">
                                <textarea name="message" 
                                          class="form-control message-input" 
                                          placeholder="Type your message here..." 
                                          rows="1"
                                          required></textarea>
                                <button type="submit" class="btn-send">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar - Right Column -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
            <div class="actions-card mb-4">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h5 class="mb-0">Actions</h5>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Assign Staff (Admin only) -->
                    @if(auth()->user()->isAdmin())
                    <div class="action-section">
                        <h6 class="action-title">
                            <i class="fas fa-user-plus me-2"></i>
                            Assign Staff
                        </h6>
                        <div class="assign-staff">
                            <select id="staffSelect" class="form-select mb-2">
                                <option value="">Select staff member...</option>
                                @foreach(\App\Models\User::where('role', 'staff')->get() as $staff)
                                    <option value="{{ $staff->id }}" 
                                        {{ $serviceRequest->assigned_staff_id == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn-action w-100" onclick="assignStaff()">
                                <i class="fas fa-check me-2"></i>
                                Assign Staff
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Update Status -->
                    <div class="action-section">
                        <h6 class="action-title">
                            <i class="fas fa-sync-alt me-2"></i>
                            Update Status
                        </h6>
                        <div class="update-status">
                            <select id="statusSelect" class="form-select mb-2">
                                <option value="pending" {{ $serviceRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $serviceRequest->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $serviceRequest->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rejected" {{ $serviceRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <button class="btn-action update-status-btn w-100" onclick="updateStatus()">
                                <i class="fas fa-arrow-right me-2"></i>
                                Update Status
                            </button>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="action-section quick-actions">
                        <h6 class="action-title">
                            <i class="fas fa-bolt me-2"></i>
                            Quick Actions
                        </h6>
                        <div class="quick-action-buttons">
                            <a href="{{ route('requests.edit', $serviceRequest) }}" class="quick-action-btn">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </a>
                            <button class="quick-action-btn" onclick="window.print()">
                                <i class="fas fa-print"></i>
                                <span>Print</span>
                            </button>
                            @if(auth()->user()->isAdmin())
                            <button class="quick-action-btn delete-btn" onclick="deleteRequest()">
                                <i class="fas fa-trash"></i>
                                <span>Delete</span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Feedback Card - For Citizens -->
            @if($serviceRequest->status == 'completed' && auth()->user()->isCitizen() && auth()->id() == $serviceRequest->user_id && !$serviceRequest->feedback)
            <div class="feedback-card mb-4">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="mb-0">Leave Feedback</h5>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('feedback.store', $serviceRequest) }}" method="POST" id="feedbackForm">
                        @csrf
                        
                        <!-- Rating Stars -->
                        <div class="rating-section">
                            <label class="form-label">How would you rate this service?</label>
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" required>
                                    <label for="star{{ $i }}" class="star-label">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Comment -->
                        <div class="form-group mt-3">
                            <label for="comment" class="form-label">Additional Comments (Optional)</label>
                            <textarea name="comment" 
                                      id="comment" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Share your experience..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit-feedback w-100 mt-3">
                            <i class="fas fa-paper-plane me-2"></i>
                            Submit Feedback
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Display Feedback -->
            @if($serviceRequest->feedback)
            <div class="feedback-display-card">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="mb-0">Feedback</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="rating-display">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $serviceRequest->feedback->rating ? 'rated' : '' }}"></i>
                        @endfor
                    </div>
                    
                    @if($serviceRequest->feedback->comment)
                        <p class="feedback-comment">"{{ $serviceRequest->feedback->comment }}"</p>
                    @endif
                    
                    <div class="feedback-meta">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ $serviceRequest->feedback->user->name }}
                        <span class="mx-2">•</span>
                        <i class="far fa-clock me-1"></i>
                        {{ $serviceRequest->feedback->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Timeline Card -->
            <div class="timeline-card">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h5 class="mb-0">Request Timeline</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="timeline">
                        <!-- Created Event -->
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Request Created</h6>
                                <p>{{ $serviceRequest->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Status Changes -->
                        @foreach($serviceRequest->statusHistory ?? [] as $change)
                        <div class="timeline-item">
                            <div class="timeline-icon status-{{ $change->status }}">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Status Changed to {{ ucfirst($change->status) }}</h6>
                                <p>By {{ $change->user->name }} • {{ $change->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach

                        <!-- Assignment Events -->
                        @if($serviceRequest->assignedStaff)
                        <div class="timeline-item">
                            <div class="timeline-icon assigned">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Assigned to {{ $serviceRequest->assignedStaff->name }}</h6>
                                <p>{{ $serviceRequest->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Request Image">
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this request? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('requests.destroy', $serviceRequest) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Request</button>
                </form>
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

    .status-badge-large {
        padding: 0.5rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
    }

    /* Card Styles */
    .details-card,
    .messages-card,
    .actions-card,
    .feedback-card,
    .feedback-display-card,
    .timeline-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card-header-custom {
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

    /* Details Grid */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .detail-item {
        padding: 1rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-dark);
        border-radius: 12px;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-label {
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-label i {
        color: var(--orange-primary);
    }

    .detail-value {
        color: var(--text-primary);
        font-size: 1rem;
        line-height: 1.5;
    }

    .description-text {
        white-space: pre-line;
    }

    .category-badge {
        display: inline-block;
        padding: 0.25rem 1rem;
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .user-info,
    .staff-info {
        display: flex;
        flex-direction: column;
    }

    .user-info i,
    .staff-info i {
        color: var(--orange-primary);
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }

    /* Image Gallery */
    .image-gallery {
        position: relative;
        display: inline-block;
    }

    .request-image {
        max-width: 100%;
        max-height: 300px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .request-image:hover {
        transform: scale(1.02);
        border-color: var(--orange-primary);
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.2);
    }

    /* Messages */
    .messages-container {
        height: 400px;
        overflow-y: auto;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .message-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .message-item.own-message {
        flex-direction: row-reverse;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange-primary);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .message-content {
        flex: 1;
        max-width: 70%;
    }

    .own-message .message-content {
        text-align: right;
    }

    .message-header {
        margin-bottom: 0.25rem;
    }

    .message-sender {
        font-weight: 600;
        color: var(--text-primary);
        margin-right: 0.5rem;
    }

    .message-time {
        color: var(--text-secondary);
        font-size: 0.75rem;
    }

    .message-body {
        background: rgba(255, 255, 255, 0.05);
        padding: 0.75rem 1rem;
        border-radius: 12px;
        color: var(--text-primary);
        word-wrap: break-word;
    }

    .own-message .message-body {
        background: var(--orange-primary);
        color: white;
    }

    .empty-messages {
        text-align: center;
        padding: 3rem;
        color: var(--text-secondary);
    }

    .empty-messages i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--orange-primary);
        opacity: 0.5;
    }

    /* Message Input */
    .message-input-container {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        padding: 1rem;
    }

    .message-form .input-group {
        display: flex;
        gap: 0.5rem;
    }

    .message-input {
        flex: 1;
        background: var(--input-bg);
        border: 1px solid var(--border-dark);
        border-radius: 10px !important;
        color: var(--text-primary);
        padding: 0.75rem 1rem;
        resize: none;
    }

    .message-input:focus {
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        outline: none;
    }

    .btn-send {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-send:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
    }

    /* Actions Card */
    .action-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-dark);
    }

    .action-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .action-title {
        color: var(--text-secondary);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .btn-action {
        padding: 0.75rem 1rem;
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 10px;
        color: var(--orange-primary);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background: var(--orange-primary);
        color: white;
        transform: translateY(-2px);
    }

    .update-status-btn {
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
    }

    .quick-action-buttons {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
    }

    .quick-action-btn {
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-dark);
        border-radius: 10px;
        color: var(--text-secondary);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.3s ease;
    }

    .quick-action-btn:hover {
        background: var(--orange-primary);
        color: white;
        transform: translateY(-2px);
    }

    .quick-action-btn.delete-btn:hover {
        background: #ef4444;
    }

    /* Star Rating */
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 0.25rem;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 1.5rem;
        color: var(--border-dark);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #fbbf24;
    }

    .rating-display {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 1rem;
    }

    .rating-display i {
        font-size: 1.2rem;
        color: var(--border-dark);
    }

    .rating-display i.rated {
        color: #fbbf24;
    }

    .feedback-comment {
        color: var(--text-primary);
        font-style: italic;
        margin-bottom: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

    .feedback-meta {
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .btn-submit-feedback {
        padding: 0.75rem;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-submit-feedback:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-dark);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-icon {
        position: absolute;
        left: -2rem;
        width: 30px;
        height: 30px;
        background: var(--dark-card);
        border: 2px solid var(--border-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 0.8rem;
        z-index: 1;
    }

    .timeline-icon.status-pending { border-color: #f59e0b; color: #f59e0b; }
    .timeline-icon.status-in_progress { border-color: #3b82f6; color: #3b82f6; }
    .timeline-icon.status-completed { border-color: #10b981; color: #10b981; }
    .timeline-icon.assigned { border-color: var(--orange-primary); color: var(--orange-primary); }

    .timeline-content {
        padding-left: 1rem;
    }

    .timeline-content h6 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .timeline-content p {
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin-bottom: 0;
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

    /* Responsive */
    @media (max-width: 768px) {
        .page-header .d-flex {
            flex-direction: column;
            align-items: start;
            gap: 1rem;
        }
        
        .header-actions {
            margin-left: 0 !important;
            width: 100%;
        }
        
        .status-badge-large {
            width: 100%;
            justify-content: center;
        }
        
        .details-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-action-buttons {
            grid-template-columns: 1fr;
        }
        
        .message-item .message-content {
            max-width: 85%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Message scrolling
    const messageContainer = document.getElementById('messageContainer');
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }

    // Message form submission
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const messageInput = form.find('textarea[name="message"]');
        const messageText = messageInput.val().trim();

        if (!messageText) {
            return;
        }

        const submitBtn = form.find('button[type="submit"]');
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: '{{ route("messages.store") }}',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    messageContainer.innerHTML += response.html;
                    messageInput.val('');
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                }
            },
            error: function(xhr) {
                alert('Error sending message. Please try again.');
            },
            complete: function() {
                submitBtn.html('<i class="fas fa-paper-plane"></i>').prop('disabled', false);
            }
        });
    });

    // Auto-resize textarea
    $('.message-input').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Poll for new messages
    let lastMessageId = $('.message-item:last').data('id') || 0;
    
    setInterval(function() {
        $.get('{{ route("messages.poll", ["user" => ($serviceRequest->assigned_staff_id ?? $serviceRequest->user_id)]) }}?service_request_id={{ $serviceRequest->id }}&last_id=' + lastMessageId, 
            function(response) {
                if (response.messages && response.messages.length > 0) {
                    response.messages.forEach(function(message) {
                        messageContainer.innerHTML += message.html;
                        lastMessageId = message.id;
                    });
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                }
        });
    }, 3000);

    // Staff assignment
    function assignStaff() {
        const staffId = $('#staffSelect').val();
        if (!staffId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a staff member.',
                background: '#1e2329',
                color: '#f3f4f6'
            });
            return;
        }

        $.ajax({
            url: '{{ route("requests.assign-staff", $serviceRequest) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                staff_id: staffId
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Staff assigned successfully!',
                    background: '#1e2329',
                    color: '#f3f4f6'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error assigning staff. Please try again.',
                    background: '#1e2329',
                    color: '#f3f4f6'
                });
            }
        });
    }

    // Update status
    function updateStatus() {
        const status = $('#statusSelect').val();
        
        Swal.fire({
            title: 'Update Status',
            text: `Are you sure you want to update the status to ${status.replace('_', ' ')}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, update it!',
            background: '#1e2329',
            color: '#f3f4f6'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("requests.update", $serviceRequest) }}',
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'Status updated successfully.',
                            background: '#1e2329',
                            color: '#f3f4f6'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error updating status.',
                            background: '#1e2329',
                            color: '#f3f4f6'
                        });
                    }
                });
            }
        });
    }

    // Delete request
    function deleteRequest() {
        $('#deleteModal').modal('show');
    }

    // Image modal
    function openImageModal(src) {
        $('#modalImage').attr('src', src);
        $('#imageModal').modal('show');
    }

    // Add SweetAlert if not already included
    if (typeof Swal === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(script);
    }
</script>
@endpush
