<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function store(Request $request, $serviceRequestId = null)
    {
        // Support both route model binding (web) and body param (API)
        if ($serviceRequestId) {
            $serviceRequest = ServiceRequest::findOrFail($serviceRequestId);
        } else {
            $request->validate(['service_request_id' => 'required|exists:service_requests,id']);
            $serviceRequest = ServiceRequest::findOrFail($request->service_request_id);
        }

        if ($serviceRequest->status !== 'completed') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You can only provide feedback for completed requests.'], 422);
            }
            return redirect()->back()->with('error', 'You can only provide feedback for completed requests.');
        }

        if ($serviceRequest->user_id !== Auth::id()) {
            abort(403);
        }

        if ($serviceRequest->feedback) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Feedback already provided for this request.'], 422);
            }
            return redirect()->back()->with('error', 'Feedback already provided for this request.');
        }

        $validator = Validator::make($request->all(), [
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $feedback = Feedback::create([
            'user_id'            => Auth::id(),
            'service_request_id' => $serviceRequest->id,
            'rating'             => $request->rating,
            'comment'            => $request->comment,
        ]);

        if ($request->expectsJson()) {
            return response()->json($feedback, 201);
        }

        return redirect()->route('requests.show', $serviceRequest)
            ->with('success', 'Thank you for your feedback!');
    }

    public function index(Request $request)
    {
        $feedback = Feedback::with(['user', 'serviceRequest'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        if ($request->expectsJson()) {
            return response()->json($feedback);
        }

        return view('feedback.index', compact('feedback'));
    }

    public function show(Request $request, $id)
    {
        $feedback = Feedback::with(['user', 'serviceRequest'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json($feedback);
    }

    public function adminIndex(Request $request)
    {
        $feedback = Feedback::with(['user', 'serviceRequest'])->latest()->paginate(15);

        if ($request->expectsJson()) {
            return response()->json($feedback);
        }

        return view('feedback.index', compact('feedback'));
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted.']);
    }
}
