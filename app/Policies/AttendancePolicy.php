<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role->isAdmin() || $user->role->isHr();
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->role->isAdmin() || $user->role->isHr() || $attendance->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true; // TODO: Decide if this should just be true for all roles.
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->role->isAdmin() || $user->role->isHr() || $attendance->user_id === $user->id;
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->role->isAdmin();
    }
}