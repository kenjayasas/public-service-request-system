@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('messages.index') }}" class="btn-back me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="user-avatar">
                            <i class="fas fa-user-circle"></i>
                            @if($user->last_activity && $user->last_activity->gt(now()->subMinutes(5)))
                                <span class="online-indicator"></span>
                            @endif
                        </div>
                        <div>
                            <h4 class="chat-title">
                                {{ $user->name }}
                                @if($user->isStaff())
                                    <span class="role-badge staff">
                                        <i class="fas fa-user-tie"></i> Staff
                                    </span>
                                @elseif($user->isAdmin())
                                    <span class="role-badge admin">
                                        <i class="fas fa-crown"></i> Admin
                                    </span>
                                @else
                                    <span class="role-badge citizen">
                                        <i class="fas fa-user"></i> Citizen
                                    </span>
                                @endif
                            </h4>
                            @if(isset($serviceRequest))
                                <div class="request-context">
                                    <i class="fas fa-ticket-alt"></i>
                                    Re: Request #{{ $serviceRequest->id }} - {{ $serviceRequest->title }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button class="btn-action" onclick="refreshChat()" data-tooltip="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button class="btn-action" onclick="toggleInfo()" data-tooltip="User Info">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="chat-container">
                <!-- Messages Area -->
                <div class="messages-area" id="message-container">
                    @forelse($messages as $message)
                        <div class="message-wrapper {{ $message->sender_id == Auth::id() ? 'own-message' : 'other-message' }}" 
                             data-id="{{ $message->id }}">
                            
                            @if($message->sender_id != Auth::id())
                                <div class="message-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                            @endif
                            
                            <div class="message-content">
                                <div class="message-bubble">
                                    @if($message->sender_id != Auth::id())
                                        <div class="message-sender-info">
                                            <span class="sender-name">{{ $message->sender->name }}</span>
                                            <span class="message-time">{{ $message->created_at->format('h:i A') }}</span>
                                        </div>
                                    @endif
                                    
                                    <p class="message-text">{{ $message->message }}</p>
                                    
                                    @if($message->sender_id == Auth::id())
                                        <div class="message-status">
                                            @if($message->is_read)
                                                <span class="status read" data-tooltip="Read">
                                                    <i class="fas fa-check-double"></i>
                                                    <span class="time">{{ $message->created_at->format('h:i A') }}</span>
                                                </span>
                                            @else
                                                <span class="status sent" data-tooltip="Sent">
                                                    <i class="fas fa-check"></i>
                                                    <span class="time">{{ $message->created_at->format('h:i A') }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="message-time-other">
                                            {{ $message->created_at->format('h:i A') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-messages">
                            <div class="empty-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h5>No messages yet</h5>
                            <p>Start the conversation by sending a message below</p>
                        </div>
                    @endforelse
                </div>

                <!-- User Info Sidebar (Hidden by default) -->
                <div class="user-info-sidebar" id="userInfoSidebar">
                    <div class="info-header">
                        <h5>User Information</h5>
                        <button class="btn-close-info" onclick="toggleInfo()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="info-content">
                        <div class="info-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h6>{{ $user->name }}</h6>
                        <p class="user-email">{{ $user->email }}</p>
                        
                        <div class="info-details">
                            <div class="info-item">
                                <span class="info-label">Role</span>
                                <span class="info-value">
                                    @if($user->isStaff())
                                        <span class="role-badge staff">Staff</span>
                                    @elseif($user->isAdmin())
                                        <span class="role-badge admin">Admin</span>
                                    @else
                                        <span class="role-badge citizen">Citizen</span>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Member since</span>
                                <span class="info-value">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Last active</span>
                                <span class="info-value">
                                    @if($user->last_activity)
                                        {{ $user->last_activity->diffForHumans() }}
                                    @else
                                        Unknown
                                    @endif
                                </span>
                            </div>
                            
                            @if(isset($serviceRequest))
                                <div class="info-divider"></div>
                                <h6>Related Request</h6>
                                <div class="related-request">
                                    <div class="request-id">#{{ $serviceRequest->id }}</div>
                                    <div class="request-title">{{ $serviceRequest->title }}</div>
                                    <div class="request-status status-{{ $serviceRequest->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                                    </div>
                                    <a href="{{ route('requests.show', $serviceRequest) }}" class="btn-view-request">
                                        <i class="fas fa-eye me-2"></i>
                                        View Request
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="message-input-container">
                <form id="message-form" class="message-form">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                    @if(isset($serviceRequest))
                        <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                    @endif
                    
                    <div class="input-wrapper">
                        <textarea name="message" 
                                  id="message-input" 
                                  class="message-input-field" 
                                  placeholder="Type your message here..."
                                  rows="1"
                                  required></textarea>
                        
                        <div class="input-actions">
                            <button type="button" class="input-action" onclick="attachFile()" data-tooltip="Attach file">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <button type="button" class="input-action" onclick="addEmoji()" data-tooltip="Add emoji">
                                <i class="fas fa-smile"></i>
                            </button>
                            <button type="submit" class="send-button" id="sendBtn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Attachment Modal -->
<div class="modal fade" id="attachmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attach File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="upload-area" id="attachmentUpload">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h6>Drag & Drop or Click to Upload</h6>
                    <p class="text-secondary">Max file size: 5MB</p>
                    <input type="file" id="fileInput" style="display: none;">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Chat Header */
    .chat-header {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 20px 20px 0 0;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-back {
        width: 40px;
        height: 40px;
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 10px;
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

    .user-avatar {
        position: relative;
        margin-right: 1rem;
    }

    .user-avatar i {
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

    .chat-title {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .role-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.75rem;
        border-radius: 20px;
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

    .request-context {
        font-size: 0.85rem;
        color: var(--text-secondary);
        background: rgba(249, 115, 22, 0.1);
        padding: 0.25rem 1rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .request-context i {
        color: var(--orange-primary);
    }

    .chat-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        background: transparent;
        border: 1px solid var(--border-dark);
        border-radius: 8px;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background: var(--orange-primary);
        color: white;
    }

    /* Chat Container */
    .chat-container {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-top: none;
        border-bottom: none;
        display: flex;
        position: relative;
        min-height: 500px;
    }

    .messages-area {
        flex: 1;
        height: 500px;
        overflow-y: auto;
        padding: 1.5rem;
        scroll-behavior: smooth;
    }

    /* Message Styles */
    .message-wrapper {
        display: flex;
        margin-bottom: 1.5rem;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .own-message {
        justify-content: flex-end;
    }

    .other-message {
        justify-content: flex-start;
    }

    .message-avatar {
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .message-avatar i {
        font-size: 2rem;
        color: var(--orange-primary);
    }

    .message-content {
        max-width: 60%;
    }

    .own-message .message-content {
        order: 1;
    }

    .message-bubble {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-dark);
        border-radius: 18px;
        padding: 0.75rem 1rem;
        position: relative;
    }

    .own-message .message-bubble {
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-color: var(--orange-primary);
    }

    .message-sender-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
    }

    .sender-name {
        font-weight: 600;
        color: var(--orange-primary);
        font-size: 0.85rem;
    }

    .message-time {
        color: var(--text-secondary);
        font-size: 0.7rem;
    }

    .own-message .message-time {
        color: rgba(255, 255, 255, 0.7);
    }

    .message-text {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        word-wrap: break-word;
        line-height: 1.5;
    }

    .own-message .message-text {
        color: white;
    }

    .message-status {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.25rem;
    }

    .status {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.7rem;
    }

    .status.read {
        color: #4ade80;
    }

    .status.sent {
        color: var(--text-secondary);
    }

    .own-message .status.sent {
        color: rgba(255, 255, 255, 0.7);
    }

    .time {
        color: inherit;
    }

    .message-time-other {
        color: var(--text-secondary);
        font-size: 0.7rem;
        margin-top: 0.25rem;
        text-align: right;
    }

    /* Empty Messages */
    .empty-messages {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
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
        margin-bottom: 1rem;
        font-size: 2.5rem;
        color: var(--orange-primary);
    }

    .empty-messages h5 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .empty-messages p {
        color: var(--text-secondary);
    }

    /* User Info Sidebar */
    .user-info-sidebar {
        width: 300px;
        background: rgba(0, 0, 0, 0.3);
        border-left: 1px solid var(--border-dark);
        display: none;
        flex-direction: column;
    }

    .user-info-sidebar.show {
        display: flex;
    }

    .info-header {
        padding: 1rem;
        border-bottom: 1px solid var(--border-dark);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-header h5 {
        color: var(--text-primary);
        margin-bottom: 0;
    }

    .btn-close-info {
        background: transparent;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .btn-close-info:hover {
        color: var(--orange-primary);
    }

    .info-content {
        padding: 1.5rem;
        text-align: center;
    }

    .info-avatar i {
        font-size: 4rem;
        color: var(--orange-primary);
        margin-bottom: 1rem;
    }

    .info-content h6 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .user-email {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .info-details {
        text-align: left;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-dark);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .info-value {
        color: var(--text-primary);
        font-weight: 500;
    }

    .info-divider {
        height: 1px;
        background: var(--border-dark);
        margin: 1.5rem 0;
    }

    .related-request {
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 0.5rem;
    }

    .request-id {
        color: var(--orange-primary);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .request-title {
        color: var(--text-primary);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .request-status {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }

    .request-status.status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .request-status.status-in_progress {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }

    .request-status.status-completed {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .btn-view-request {
        display: block;
        text-align: center;
        padding: 0.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-view-request:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
    }

    /* Message Input */
    .message-input-container {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-top: none;
        border-radius: 0 0 20px 20px;
        padding: 1.5rem;
    }

    .message-form {
        width: 100%;
    }

    .input-wrapper {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-dark);
        border-radius: 12px;
        padding: 0.5rem;
    }

    .message-input-field {
        flex: 1;
        background: transparent;
        border: none;
        color: var(--text-primary);
        font-size: 1rem;
        padding: 0.5rem;
        resize: none;
        min-height: 40px;
        max-height: 120px;
    }

    .message-input-field:focus {
        outline: none;
    }

    .message-input-field::placeholder {
        color: var(--text-muted);
    }

    .input-actions {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .input-action {
        width: 36px;
        height: 36px;
        background: transparent;
        border: none;
        border-radius: 8px;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-action:hover {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
    }

    .send-button {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 10px;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .send-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
    }

    .send-button i {
        font-size: 1.2rem;
    }

    /* Attachment Modal */
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

    .upload-area {
        background: rgba(249, 115, 22, 0.05);
        border: 2px dashed var(--border-dark);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: var(--orange-primary);
        background: rgba(249, 115, 22, 0.1);
    }

    .upload-area i {
        font-size: 3rem;
        color: var(--orange-primary);
        margin-bottom: 1rem;
    }

    .upload-area h6 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
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
        z-index: 1000;
    }

    [data-tooltip]:hover:before {
        opacity: 1;
        visibility: visible;
    }

    /* Scrollbar */
    .messages-area::-webkit-scrollbar {
        width: 6px;
    }

    .messages-area::-webkit-scrollbar-track {
        background: var(--dark-secondary);
    }

    .messages-area::-webkit-scrollbar-thumb {
        background: var(--border-dark);
        border-radius: 3px;
    }

    .messages-area::-webkit-scrollbar-thumb:hover {
        background: var(--orange-primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .message-content {
            max-width: 85%;
        }
        
        .user-info-sidebar {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
        }
        
        .chat-actions {
            margin-top: 1rem;
            width: 100%;
            justify-content: flex-end;
        }
        
        .input-wrapper {
            flex-direction: column;
            align-items: stretch;
        }
        
        .input-actions {
            justify-content: flex-end;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let lastMessageId = $('.message-wrapper:last').data('id') || 0;
    let isInfoSidebarVisible = false;

    $(document).ready(function() {
        const container = $('#message-container');
        
        // Scroll to bottom
        container.scrollTop(container[0].scrollHeight);

        // Auto-resize textarea
        $('#message-input').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Send message via AJAX
        $('#message-form').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const messageInput = $('#message-input');
            const messageText = messageInput.val().trim();
            const sendBtn = $('#sendBtn');

            if (!messageText) {
                return;
            }

            // Disable button and show loading
            sendBtn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

            $.ajax({
                url: '{{ route("messages.store") }}',
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        container.append(response.html);
                        messageInput.val('');
                        messageInput.css('height', 'auto');
                        container.scrollTop(container[0].scrollHeight);
                        lastMessageId = response.message.id;
                    }
                },
                error: function(xhr) {
                    showNotification('Error sending message', 'error');
                },
                complete: function() {
                    sendBtn.html('<i class="fas fa-paper-plane"></i>').prop('disabled', false);
                }
            });
        });

        // Poll for new messages
        setInterval(pollMessages, 3000);
    });

    // Poll for new messages
    function pollMessages() {
        $.get('{{ route("messages.poll", ["user" => $user->id]) }}' + 
               @json(isset($serviceRequest) ? '?service_request_id=' . $serviceRequest->id : '') + 
               '&last_id=' + lastMessageId, 
            function(response) {
                if (response.messages && response.messages.length > 0) {
                    const container = $('#message-container');
                    response.messages.forEach(function(message) {
                        container.append(message.html);
                        lastMessageId = message.id;
                    });
                    container.scrollTop(container[0].scrollHeight);
                    
                    // Play notification sound for new messages
                    if (response.messages.some(m => m.sender_id != {{ Auth::id() }})) {
                        playNotificationSound();
                    }
                }
        });
    }

    // Refresh chat
    function refreshChat() {
        location.reload();
    }

    // Toggle user info sidebar
    function toggleInfo() {
        const sidebar = $('#userInfoSidebar');
        isInfoSidebarVisible = !isInfoSidebarVisible;
        
        if (isInfoSidebarVisible) {
            sidebar.addClass('show');
        } else {
            sidebar.removeClass('show');
        }
    }

    // Attach file
    function attachFile() {
        $('#attachmentModal').modal('show');
    }

    // Add emoji
    function addEmoji() {
        // You can integrate an emoji picker here
        alert('Emoji picker coming soon!');
    }

    // Play notification sound
    function playNotificationSound() {
        const audio = new Audio('/sounds/notification.mp3');
        audio.play().catch(e => console.log('Audio play failed:', e));
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

    // Handle attachment upload
    $('#attachmentUpload').on('click', function() {
        $('#fileInput').click();
    });

    $('#fileInput').on('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            // Handle file upload here
            console.log('File selected:', file.name);
            $('#attachmentModal').modal('hide');
            showNotification('File attached successfully', 'success');
        }
    });

    // Drag and drop for attachment
    const uploadArea = document.getElementById('attachmentUpload');
    
    if (uploadArea) {
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                console.log('File dropped:', files[0].name);
                $('#attachmentModal').modal('hide');
                showNotification('File attached successfully', 'success');
            }
        });
    }
</script>
@endpush
