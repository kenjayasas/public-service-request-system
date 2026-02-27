<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Feedback;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function view(User $user, Feedback $feedback)
    {
        return $user->isAdmin() || $user->id === $feedback->user_id;
    }

    public function create(User $user)
    {
        return $user->isCitizen();
    }

    public function update(User $user, Feedback $feedback)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Feedback $feedback)
    {
        return $user->isAdmin();
    }
}