<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
use App\Enums\ShiftType;
use App\Enums\WorkMode;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('User')
            ->where('user_id', $request->user()->id);

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'shift_type' => 'required|in:' . implode(',', ShiftType::values()),
            'type' => 'required|in:' . implode(',', AttendanceType::values()),
            'time' => 'required|date_format:H:i',
            'work_mode' => 'sometimes|in:' . implode(',', WorkMode::values()),
            'selfie_path' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:' . implode(',', AttendanceStatus::values()),
        ]);

        $attendance = $request->user()->attendances()->create($validated);

        return response()->json($attendance, 201);
    }

    public function show(Request $request, Attendance $attendance)
    {
        if ($request->user()->cannot('view', $attendance)) abort(403);

        return response()->json($attendance->load('user'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        if ($request->user()->cannot('update', $attendance)) abort(403);

        $validated = $request->validate([
            'date' => 'sometimes|date',
            'shift_type' => 'sometimes|in:' . implode(',', ShiftType::values()),
            'type' => 'sometimes|in:' . implode(',', AttendanceType::values()),
            'time' => 'sometimes|date_format:H:i',
            'work_mode' => 'sometimes|in:' . implode(',', WorkMode::values()),
            'selfie_path' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:' . implode(',', AttendanceStatus::values()),
        ]);

        $attendance->update($validated);

        return response()->json($attendance);
    }

    public function destroy(Request $request, Attendance $attendance)
    {
        if ($request->user()->cannot('delete', $attendance)) abort(403);

        $attendance->delete();
        return response()->noContent();
    }

    public function export(Request $request)
    {
        if ($request->user()->cannot('export', Attendance::class)) abort(403);
        
        // TODO: Implement export logic (CSV, PDF, etc.)
        return response()->json(['message' => 'Export functionality']);
    }
}