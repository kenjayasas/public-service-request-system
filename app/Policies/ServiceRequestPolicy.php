<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ServiceRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // All authenticated users can view list (filtered by controller)
    }

    public function view(User $user, ServiceRequest $serviceRequest)
    {
        return $user->isAdmin() || 
               $user->isStaff() || 
               $user->id === $serviceRequest->user_id;
    }

    public function create(User $user)
    {
        return $user->isCitizen(); // Only citizens can create requests
    }

    public function update(User $user, ServiceRequest $serviceRequest)
    {
        return $user->isAdmin() || 
               ($user->isStaff() && $serviceRequest->assigned_staff_id === $user->id);
    }

    public function delete(User $user, ServiceRequest $serviceRequest)
    {
        return $user->isAdmin();
    }

    public function assignStaff(User $user, ServiceRequest $serviceRequest)
    {
        return $user->isAdmin();
    }

    public function addFeedback(User $user, ServiceRequest $serviceRequest)
    {
        return $user->id === $serviceRequest->user_id && 
               $serviceRequest->status === 'completed' &&
               !$serviceRequest->feedback;
    }
}