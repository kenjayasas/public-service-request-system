@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div>
                        <h2 class="page-title">Messages</h2>
                        <p class="page-subtitle">Manage your conversations and communications</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <div class="stat-details">
                    <h3>{{ $conversations->count() }}</h3>
                    <p>Total Conversations</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card unread">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-details">
                    @php
                        $totalUnread = 0;
                        foreach($conversations as $messages) {
                            $totalUnread += $messages->where('receiver_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        }
                    @endphp
                    <h3>{{ $totalUnread }}</h3>
                    <p>Unread Messages</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    @php
                        $latestMessage = collect($conversations)->flatten()->sortByDesc('created_at')->first();
                    @endphp
                    <h3>{{ $latestMessage ? $latestMessage->created_at->diffForHumans() : 'N/A' }}</h3>
                    <p>Latest Activity</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversations Grid -->
    <div class="row">
        <div class="col-12">
            <div class="conversations-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2" style="color: var(--orange-primary);"></i>
                            Your Conversations
                        </h5>
                        <div class="header-actions">
                            <span class="item-count">{{ $conversations->count() }} conversations</span>
                            <button class="btn-refresh" onclick="refreshConversations()" data-tooltip="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($conversations->count() > 0)
                        <div class="conversations-list">
                            @foreach($conversations as $key => $messages)
                                @php
                                    $lastMessage = $messages->first();
                                    $otherUser = $lastMessage->sender_id == auth()->id() 
                                        ? $lastMessage->receiver 
                                        : $lastMessage->sender;
                                    $unreadCount = $messages->where('receiver_id', auth()->id())
                                        ->where('is_read', false)
                                        ->count();
                                    $isOnline = $otherUser->last_activity && $otherUser->last_activity->gt(now()->subMinutes(5));
                                @endphp
                                
                                <a href="{{ route('messages.show', ['user' => $otherUser->id, 'service_request' => $lastMessage->service_request_id]) }}" 
                                   class="conversation-item {{ $unreadCount > 0 ? 'unread' : '' }}">
                                    
                                    <!-- User Avatar -->
                                    <div class="conversation-avatar">
                                        <div class="avatar-wrapper">
                                            <i class="fas fa-user-circle"></i>
                                            @if($isOnline)
                                                <span class="online-indicator"></span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Conversation Content -->
                                    <div class="conversation-content">
                                        <div class="conversation-header">
                                            <h6 class="user-name">
                                                {{ $otherUser->name }}
                                                @if($otherUser->isStaff())
                                                    <span class="role-badge staff">
                                                        <i class="fas fa-user-tie"></i> Staff
                                                    </span>
                                                @elseif($otherUser->isAdmin())
                                                    <span class="role-badge admin">
                                                        <i class="fas fa-crown"></i> Admin
                                                    </span>
                                                @else
                                                    <span class="role-badge citizen">
                                                        <i class="fas fa-user"></i> Citizen
                                                    </span>
                                                @endif
                                            </h6>
                                            <span class="message-time">
                                                <i class="far fa-clock"></i>
                                                {{ $lastMessage->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        <div class="conversation-preview">
                                            @if($lastMessage->serviceRequest)
                                                <span class="request-badge">
                                                    <i class="fas fa-ticket-alt"></i>
                                                    Request #{{ $lastMessage->serviceRequest->id }}: 
                                                    {{ Str::limit($lastMessage->serviceRequest->title, 30) }}
                                                </span>
                                            @endif
                                            
                                            <div class="message-preview {{ $unreadCount > 0 ? 'fw-bold' : '' }}">
                                                @if($lastMessage->sender_id == auth()->id())
                                                    <span class="text-secondary">You: </span>
                                                @endif
                                                {{ Str::limit($lastMessage->message, 60) }}
                                            </div>
                                        </div>
                                        
                                        <!-- Message Status Indicators -->
                                        <div class="conversation-footer">
                                            <div class="message-status">
                                                @if($lastMessage->sender_id == auth()->id())
                                                    @if($lastMessage->is_read)
                                                        <span class="status read" data-tooltip="Read">
                                                            <i class="fas fa-check-double"></i>
                                                        </span>
                                                    @else
                                                        <span class="status sent" data-tooltip="Sent">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            
                                            @if($unreadCount > 0)
                                                <span class="unread-badge">{{ $unreadCount }} new</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Hover Actions -->
                                    <div class="conversation-hover-actions">
                                        <button class="hover-action" onclick="event.preventDefault(); markAsRead('{{ $key }}')" data-tooltip="Mark as read">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                        <button class="hover-action" onclick="event.preventDefault(); archiveConversation('{{ $key }}')" data-tooltip="Archive">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3>No Conversations Yet</h3>
                            <p>When you start communicating with staff or citizens, your conversations will appear here.</p>
                            <div class="empty-actions">
                                <a href="{{ route('requests.index') }}" class="btn-empty">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    Browse Requests
                                </a>
                                <a href="{{ route('requests.create') }}" class="btn-empty">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    New Request
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Compose Section (Optional) -->
    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
    <div class="row mt-4">
        <div class="col-12">
            <div class="compose-card">
                <div class="compose-icon">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <div class="compose-content">
                    <h6>Quick Message</h6>
                    <p>Send a quick message to a citizen or staff member</p>
                </div>
                <button class="btn-compose" onclick="openComposeModal()">
                    <i class="fas fa-plus-circle me-2"></i>
                    Compose New
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Compose Modal -->
<div class="modal fade" id="composeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-pen-alt me-2" style="color: var(--orange-primary);"></i>
                    Compose Message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="composeForm">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Recipient</label>
                        <select name="receiver_id" class="form-select" required>
                            <option value="">Select recipient...</option>
                            @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                @foreach(\App\Models\User::where('role', 'citizen')->get() as $citizen)
                                    <option value="{{ $citizen->id }}">{{ $citizen->name }} (Citizen)</option>
                                @endforeach
                            @endif
                            @if(auth()->user()->isCitizen())
                                @foreach(\App\Models\User::whereIn('role', ['admin', 'staff'])->get() as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }} ({{ ucfirst($staff->role) }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Related Request (Optional)</label>
                        <select name="service_request_id" class="form-select">
                            <option value="">No related request</option>
                            @foreach(auth()->user()->serviceRequests as $request)
                                <option value="{{ $request->id }}">#{{ $request->id }} - {{ Str::limit($request->title, 30) }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendCompose()">
                    <i class="fas fa-paper-plane me-2"></i>
                    Send Message
                </button>
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

    .unread .stat-icon {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .active .stat-icon {
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

    /* Conversations Card */
    .conversations-card {
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

    .btn-refresh {
        width: 36px;
        height: 36px;
        background: transparent;
        border: 1px solid var(--border-dark);
        border-radius: 8px;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-refresh:hover {
        background: var(--orange-primary);
        color: white;
        transform: rotate(180deg);
    }

    /* Conversations List */
    .conversations-list {
        display: flex;
        flex-direction: column;
    }

    .conversation-item {
        display: flex;
        align-items: center;
        padding: 1.25rem 1.5rem;
        background: rgba(255, 255, 255, 0.02);
        border-bottom: 1px solid var(--border-dark);
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
    }

    .conversation-item:last-child {
        border-bottom: none;
    }

    .conversation-item:hover {
        background: rgba(249, 115, 22, 0.05);
        transform: translateX(5px);
    }

    .conversation-item.unread {
        background: rgba(249, 115, 22, 0.1);
        border-left: 4px solid var(--orange-primary);
    }

    /* Avatar */
    .conversation-avatar {
        margin-right: 1.25rem;
    }

    .avatar-wrapper {
        position: relative;
        width: 50px;
        height: 50px;
    }

    .avatar-wrapper i {
        font-size: 3rem;
        color: var(--orange-primary);
    }

    .online-indicator {
        position: absolute;
        bottom: 5px;
        right: 0;
        width: 12px;
        height: 12px;
        background: #10b981;
        border: 2px solid var(--dark-card);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    /* Conversation Content */
    .conversation-content {
        flex: 1;
        min-width: 0;
    }

    .conversation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
    }

    .user-name {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .role-badge {
        font-size: 0.7rem;
        padding: 0.15rem 0.5rem;
        border-radius: 12px;
        font-weight: 500;
    }

    .role-badge.staff {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.2);
    }

    .role-badge.admin {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        border: 1px solid rgba(249, 115, 22, 0.2);
    }

    .role-badge.citizen {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border: 1px solid var(--border-dark);
    }

    .message-time {
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    .message-time i {
        margin-right: 0.25rem;
        font-size: 0.7rem;
    }

    /* Preview */
    .conversation-preview {
        margin-bottom: 0.25rem;
    }

    .request-badge {
        display: inline-block;
        font-size: 0.75rem;
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        margin-bottom: 0.25rem;
    }

    .message-preview {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .message-preview.fw-bold {
        color: var(--text-primary);
    }

    /* Footer */
    .conversation-footer {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .message-status {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status {
        font-size: 0.8rem;
    }

    .status.read {
        color: #10b981;
    }

    .status.sent {
        color: var(--text-secondary);
    }

    .unread-badge {
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
        padding: 0.2rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Hover Actions */
    .conversation-hover-actions {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        gap: 0.5rem;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .conversation-item:hover .conversation-hover-actions {
        opacity: 1;
    }

    .hover-action {
        width: 30px;
        height: 30px;
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 6px;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .hover-action:hover {
        background: var(--orange-primary);
        color: white;
        border-color: var(--orange-primary);
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
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        max-width: 400px;
        margin: 0 auto 1.5rem;
    }

    .empty-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn-empty {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
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

    /* Compose Card */
    .compose-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .compose-icon {
        width: 50px;
        height: 50px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange-primary);
        font-size: 1.5rem;
    }

    .compose-content {
        flex: 1;
    }

    .compose-content h6 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .compose-content p {
        color: var(--text-secondary);
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .btn-compose {
        padding: 0.6rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-compose:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
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

    .form-label {
        color: var(--text-primary);
        font-weight: 500;
    }

    .form-select,
    .form-control {
        background-color: var(--input-bg);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
    }

    .form-select:focus,
    .form-control:focus {
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        outline: none;
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
        .conversation-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .conversation-avatar {
            margin-right: 0;
        }
        
        .conversation-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
        
        .conversation-hover-actions {
            position: static;
            transform: none;
            opacity: 1;
            margin-top: 0.5rem;
        }
        
        .empty-actions {
            flex-direction: column;
        }
        
        .compose-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Refresh conversations
    function refreshConversations() {
        location.reload();
    }

    // Mark conversation as read
    function markAsRead(conversationKey) {
        // AJAX call to mark conversation as read
        showNotification('Marked as read', 'success');
    }

    // Archive conversation
    function archiveConversation(conversationKey) {
        Swal.fire({
            title: 'Archive Conversation?',
            text: 'This conversation will be moved to archive',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, archive it!',
            background: '#1e2329',
            color: '#f3f4f6'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX call to archive
                showNotification('Conversation archived', 'success');
            }
        });
    }

    // Open compose modal
    function openComposeModal() {
        $('#composeModal').modal('show');
    }

    // Send composed message
    function sendCompose() {
        const form = $('#composeForm');
        
        $.ajax({
            url: '{{ route("messages.store") }}',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#composeModal').modal('hide');
                showNotification('Message sent successfully', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function() {
                showNotification('Error sending message', 'error');
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
