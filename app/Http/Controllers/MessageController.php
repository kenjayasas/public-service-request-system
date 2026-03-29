<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $messages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'serviceRequest'])
            ->orderBy('created_at', 'desc')
            ->get();

        $conversations = $messages->groupBy(function ($message) use ($user) {
            $otherUser = $message->sender_id == $user->id ? $message->receiver : $message->sender;
            return $otherUser->id . '-' . ($message->service_request_id ?? 'general');
        });

        if ($request->expectsJson()) {
            // Return conversations as JSON: latest message per group
            $result = $conversations->map(function ($msgs, $key) use ($user) {
                $latest    = $msgs->first();
                $otherUser = $latest->sender_id == $user->id ? $latest->receiver : $latest->sender;
                $unread    = $msgs->where('receiver_id', $user->id)->where('is_read', false)->count();
                return [
                    'key'             => $key,
                    'other_user'      => $otherUser,
                    'service_request' => $latest->serviceRequest,
                    'last_message'    => $latest->message,
                    'last_at'         => $latest->created_at,
                    'unread_count'    => $unread,
                ];
            })->values();

            return response()->json($result);
        }

        return view('messages.index', compact('conversations'));
    }

    public function show(Request $request, $userId, $serviceRequestId = null)
    {
        $currentUser = Auth::user();

        // Support both User model binding (web) and plain ID (API)
        if ($userId instanceof User) {
            $otherUser = $userId;
        } else {
            $otherUser = User::findOrFail($userId);
        }

        $query = Message::where(function ($q) use ($currentUser, $otherUser) {
            $q->where('sender_id', $currentUser->id)
              ->where('receiver_id', $otherUser->id);
        })->orWhere(function ($q) use ($currentUser, $otherUser) {
            $q->where('sender_id', $otherUser->id)
              ->where('receiver_id', $currentUser->id);
        });

        if ($serviceRequestId) {
            $query->where('service_request_id', $serviceRequestId);
        } elseif ($request->has('service_request_id')) {
            $query->where('service_request_id', $request->service_request_id);
        }

        $messages = $query->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('receiver_id', $currentUser->id)
            ->where('sender_id', $otherUser->id)
            ->update(['is_read' => true]);

        if ($request->expectsJson()) {
            return response()->json([
                'other_user' => $otherUser,
                'messages'   => $messages,
            ]);
        }

        $user           = $otherUser;
        $serviceRequest = $serviceRequestId ? ServiceRequest::find($serviceRequestId) : null;

        return view('messages.show', compact('messages', 'user', 'serviceRequest'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id'        => 'required|exists:users,id',
            'message'            => 'required|string|max:1000',
            'service_request_id' => 'nullable|exists:service_requests,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = Message::create([
            'sender_id'          => Auth::id(),
            'receiver_id'        => $request->receiver_id,
            'message'            => $request->message,
            'service_request_id' => $request->service_request_id,
        ]);

        $message->load('sender');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', 'Message sent successfully.');
    }

    public function poll(Request $request, User $user)
    {
        $query = Message::where(function ($q) use ($user) {
            $q->where('sender_id', Auth::id())
              ->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->where('receiver_id', Auth::id());
        });

        if ($request->has('service_request_id')) {
            $query->where('service_request_id', $request->service_request_id);
        }

        if ($request->has('last_id')) {
            $query->where('id', '>', $request->last_id);
        }

        $messages = $query->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        Message::where('receiver_id', Auth::id())
            ->where('sender_id', $user->id)
            ->whereIn('id', $messages->pluck('id'))
            ->update(['is_read' => true]);

        $html = '';
        foreach ($messages as $msg) {
            $html .= view('messages.partials.message', ['message' => $msg])->render();
        }

        return response()->json([
            'messages' => $messages,
            'html'     => $html,
        ]);
    }

    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead(Message $message)
    {
        if ($message->receiver_id == Auth::id()) {
            $message->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function adminIndex(Request $request)
    {
        $messages = Message::with(['sender', 'receiver', 'serviceRequest'])
            ->latest()
            ->paginate(20);

        return response()->json($messages);
    }
}
