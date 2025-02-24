<?php

namespace App\Policies;

use App\Enums\LeaveStatus;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeaveRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isHr();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LeaveRequest $leave): bool
    {
        return $user->isAdmin() || $user->isHr() || $leave->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LeaveRequest $leave): bool
    {
        return $user->isAdmin() || $user->isHr() || 
            ($leave->user_id === $user->id && $leave->isPending());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LeaveRequest $leave): bool
    {
        return $user->isAdmin() || ($leave->user_id === $user->id && $leave->isPending());
    }

    public function updateStatus(User $user): bool
    {
        return $user->isAdmin() || $user->isHr();
    }
}
