<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Attendance $attendance): bool
    {
        $employee = $user->employee()->get()->first();
        return $user->isAdmin() || $user->isHr() || $attendance->employee_id === $employee->id;
    }

    public function create(User $user): bool
    {
        return true; // TODO: Decide if this should just be true for all roles.
    }

    public function update(User $user, Attendance $attendance): bool
    {
        $employee = $user->employee()->get()->first();
        return $user->isAdmin() || $user->isHr() || $attendance->employee_id === $employee->id;
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin() || $user->isHr();
    }
}