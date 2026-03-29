<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Notifications\RequestStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ServiceRequestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = ServiceRequest::with(['user', 'category', 'assignedStaff']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Search by title or description
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // For staff, show only assigned requests
        if (Auth::user()->isStaff()) {
            $query->where('assigned_staff_id', Auth::id());
        }

        // For citizens, show only their requests
        if (Auth::user()->isCitizen()) {
            $query->where('user_id', Auth::id());
        }

        if ($request->expectsJson()) {
            $requests = $query->latest()->get();
            return response()->json($requests);
        }

        $requests = $query->latest()->paginate(10);
        $categories = ServiceCategory::all();

        return view('requests.index', compact('requests', 'categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::all();
        return view('requests.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:service_categories,id',
            'location'    => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['title', 'description', 'category_id', 'location']);
        $data['user_id'] = Auth::id();
        $data['status']  = 'pending';

        if ($request->hasFile('image')) {
            $imagePath    = $request->file('image')->store('requests', 'public');
            $data['image'] = $imagePath;
        }

        $serviceRequest = ServiceRequest::create($data);
        $serviceRequest->load(['category', 'user']);

        if ($request->expectsJson()) {
            return response()->json($serviceRequest, 201);
        }

        return redirect()->route('requests.index')
            ->with('success', 'Service request submitted successfully.');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['user', 'category', 'assignedStaff', 'feedback']);

        if (request()->expectsJson()) {
            return response()->json($serviceRequest);
        }

        return view('requests.show', compact('serviceRequest'));
    }

    public function edit(ServiceRequest $serviceRequest)
    {
        $this->authorize('update', $serviceRequest);

        $categories = ServiceCategory::all();
        $staff      = User::where('role', 'staff')->get();

        return view('requests.edit', compact('serviceRequest', 'categories', 'staff'));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorize('update', $serviceRequest);

        $validator = Validator::make($request->all(), [
            'status'            => 'sometimes|in:pending,in_progress,completed,rejected',
            'assigned_staff_id' => 'nullable|exists:users,id',
            'category_id'       => 'sometimes|exists:service_categories,id',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $oldStatus = $serviceRequest->status;
        $serviceRequest->update($request->only(['status', 'assigned_staff_id', 'category_id']));

        if ($oldStatus != $serviceRequest->status) {
            $serviceRequest->user->notify(new RequestStatusUpdated($serviceRequest));
        }

        if ($request->expectsJson()) {
            $serviceRequest->load(['user', 'category', 'assignedStaff', 'feedback']);
            return response()->json($serviceRequest);
        }

        return redirect()->route('requests.show', $serviceRequest)
            ->with('success', 'Request updated successfully.');
    }

    public function destroy(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorize('delete', $serviceRequest);

        if ($serviceRequest->image) {
            Storage::disk('public')->delete($serviceRequest->image);
        }

        $serviceRequest->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Request deleted successfully.']);
        }

        return redirect()->route('requests.index')
            ->with('success', 'Request deleted successfully.');
    }

    public function exportPDF(Request $request)
    {
        $query = ServiceRequest::with(['user', 'category', 'assignedStaff']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->get();

        $pdf = PDF::loadView('requests.pdf', compact('requests'));

        return $pdf->download('service-requests-' . now()->format('Y-m-d') . '.pdf');
    }

    public function assignStaff(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorize('assignStaff', $serviceRequest);

        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $serviceRequest->update([
            'assigned_staff_id' => $request->staff_id,
            'status'            => 'in_progress',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff assigned successfully.',
        ]);
    }

    // Admin methods
    public function adminIndex(Request $request)
    {
        $query = ServiceRequest::with(['user', 'category', 'assignedStaff']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->expectsJson()) {
            return response()->json($query->latest()->get());
        }

        return response()->json($query->latest()->paginate(10));
    }

    public function adminUpdate(Request $request, $id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status'            => 'sometimes|in:pending,in_progress,completed,rejected',
            'assigned_staff_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $serviceRequest->update($request->only(['status', 'assigned_staff_id']));

        return response()->json($serviceRequest->load(['user', 'category', 'assignedStaff']));
    }

    public function adminDestroy($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);

        if ($serviceRequest->image) {
            Storage::disk('public')->delete($serviceRequest->image);
        }

        $serviceRequest->delete();

        return response()->json(['message' => 'Request deleted successfully.']);
    }

    public function assignedIndex(Request $request)
    {
        $query = ServiceRequest::with(['user', 'category'])
            ->where('assigned_staff_id', Auth::id());

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->expectsJson()) {
            return response()->json($query->latest()->get());
        }

        return response()->json($query->latest()->paginate(10));
    }

    public function updateStatus(Request $request, $id)
    {
        $serviceRequest = ServiceRequest::where('assigned_staff_id', Auth::id())
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,completed,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $serviceRequest->update(['status' => $request->status]);

        return response()->json($serviceRequest->load(['user', 'category']));
    }
}
