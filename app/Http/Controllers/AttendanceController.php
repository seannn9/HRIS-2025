<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
use App\Enums\ShiftType;
use App\Enums\WorkMode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'sometimes|date|before_or_equal:date_to',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
            'user_id' => 'sometimes|exists:users,id',
            'shift_type' => 'sometimes|in:' . implode(',', ShiftType::values()),
            'type' => 'sometimes|in:' . implode(',', AttendanceType::values()),
            'status' => 'sometimes|in:' . implode(',', AttendanceStatus::values()),
            'work_mode' => 'sometimes|in:' . implode(',', WorkMode::values()),
            'group_by' => 'sometimes|in:user,date,shift_type',
        ]);

        $query = Attendance::with('User')
            ->when($request->user()->isEmployee(), fn($q) => $q->where('user_id', $request->user()->id))
            ->filter($validated)
            ->groupedData($validated['group_by'] ?? null);

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
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

    public function show(Request $request, string $id)
    {
        $attendance = Attendance::where('id', '=', $id)->first() ?? abort(404);

        if ($request->user()->cannot('view', $attendance)) abort(403);

        return response()->json($attendance->load('user'));
    }

    public function update(Request $request, string $id)
    {
        $attendance = Attendance::where('id', '=', $id)->first() ?? abort(404);

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

        unset($attendance['user']);
        return response()->json($attendance);
    }

    public function destroy(Request $request, string $id)
    {
        $attendance = Attendance::where('id', '=', $id)->first() ?? abort(404);

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