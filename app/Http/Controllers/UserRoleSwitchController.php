<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Enums\UserRole;
use Illuminate\Http\Request;

class UserRoleSwitchController extends Controller
{
    public function switch(Request $request) {
        $validated = $request->validate([
            'role' => 'required|in:'.implode(',', UserRole::values())
        ]);

        $role = $validated['role'];
        $request->user()->setActiveRole($role);

        return redirect()->route('dashboard')
            ->with('success', 'User role switched to '.UserRole::getLabel(UserRole::tryFrom($role)));
    }
}