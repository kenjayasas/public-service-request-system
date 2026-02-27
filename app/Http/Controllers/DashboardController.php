<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\ServiceCategory;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isStaff()) {
            return $this->staffDashboard();
        } else {
            return $this->citizenDashboard();
        }
    }

    private function adminDashboard()
    {
        $data = [
            'totalUsers' => User::count(),
            'totalRequests' => ServiceRequest::count(),
            'pendingRequests' => ServiceRequest::where('status', 'pending')->count(),
            'completedRequests' => ServiceRequest::where('status', 'completed')->count(),
            'recentRequests' => ServiceRequest::with(['user', 'category'])
                ->latest()
                ->take(5)
                ->get(),
            'monthlyStats' => $this->getMonthlyStats(),
            'requestsByCategory' => ServiceCategory::withCount('serviceRequests')->get(),
        ];

        return view('dashboard.admin', $data);
    }

    private function staffDashboard()
    {
        $staffId = Auth::id();
        
        $data = [
            'assignedRequests' => ServiceRequest::where('assigned_staff_id', $staffId)
                ->with(['user', 'category'])
                ->latest()
                ->paginate(10),
            'pendingCount' => ServiceRequest::where('assigned_staff_id', $staffId)
                ->where('status', 'pending')
                ->count(),
            'inProgressCount' => ServiceRequest::where('assigned_staff_id', $staffId)
                ->where('status', 'in_progress')
                ->count(),
            'completedCount' => ServiceRequest::where('assigned_staff_id', $staffId)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('dashboard.staff', $data);
    }

    private function citizenDashboard()
    {
        $userId = Auth::id();
        
        $data = [
            'totalRequests' => ServiceRequest::where('user_id', $userId)->count(),
            'pendingRequests' => ServiceRequest::where('user_id', $userId)
                ->where('status', 'pending')
                ->count(),
            'inProgressRequests' => ServiceRequest::where('user_id', $userId)
                ->where('status', 'in_progress')
                ->count(),
            'completedRequests' => ServiceRequest::where('user_id', $userId)
                ->where('status', 'completed')
                ->count(),
            'recentRequests' => ServiceRequest::where('user_id', $userId)
                ->with('category')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.citizen', $data);
    }

    private function getMonthlyStats()
    {
        $stats = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach (range(0, 11) as $i) {
            $month = Carbon::now()->subMonths($i);
            $stats[$months[$month->month - 1]] = ServiceRequest::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return array_reverse($stats);
    }
}