<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function store(Request $request, ServiceRequest $serviceRequest)
    {
        // Check if request is completed and belongs to user
        if ($serviceRequest->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'You can only provide feedback for completed requests.');
        }

        if ($serviceRequest->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if feedback already exists
        if ($serviceRequest->feedback) {
            return redirect()->back()
                ->with('error', 'Feedback already provided for this request.');
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Feedback::create([
            'user_id' => Auth::id(),
            'service_request_id' => $serviceRequest->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('requests.show', $serviceRequest)
            ->with('success', 'Thank you for your feedback!');
    }

    public function index()
    {

        $feedback = Feedback::with(['user', 'serviceRequest'])
            ->latest()
            ->paginate(15);

        return view('feedback.index', compact('feedback'));
    }
}