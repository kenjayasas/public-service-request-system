<div class="mb-3 {{ $message->sender_id == Auth::id() ? 'text-end' : '' }} message" 
     data-id="{{ $message->id }}">
    <div class="d-inline-block {{ $message->sender_id == Auth::id() ? 'bg-primary text-white' : 'bg-light' }}" 
         style="max-width: 70%; padding: 10px; border-radius: 10px;">
        <small class="d-block {{ $message->sender_id == Auth::id() ? 'text-white-50' : 'text-muted' }}">
            {{ $message->sender->name }} - {{ $message->created_at->format('M d, Y h:i A') }}
        </small>
        <p class="mb-0">{{ $message->message }}</p>
    </div>
</div>