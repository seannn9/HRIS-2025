<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceType;
use App\Enums\ShiftType;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceExistsController extends Controller
{
    public function check(Request $request) {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_type' => 'required|in:' . implode(',', ShiftType::values()),
            'type' => 'required|in:' . implode(',', AttendanceType::values()),
        ]);

        $employeeId = $validated['employee_id'];
        $attendanceType = $validated['type'];
        $shiftType = $validated['shift_type'];

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('created_at', '=', Carbon::now())
            ->where('type', '=', $attendanceType)
            ->where('shift_type', '=', $shiftType)
            ->latest()
            ->first();
        
        if ($attendance && $request->user()->cannot('view', $attendance)) abort(403);

        return response()->json(
            [
                'exists' => $attendance != null,
                'created_at' => $attendance ? $attendance->created_at->format('h:i A')." on ".$attendance->created_at->format('M d, Y') : null,
            ]
        );
    }
}
