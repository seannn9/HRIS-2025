<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
use App\Enums\Department;
use App\Enums\DepartmentTeam;
use App\Enums\EmploymentType;
use App\Enums\ShiftType;
use App\Enums\WorkMode;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'sometimes|date|before_or_equal:date_to',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
            'employee_id' => 'sometimes|exists:employees,id',
            'shift_type' => 'sometimes|in:' . implode(',', ShiftType::values()),
            'type' => 'sometimes|in:' . implode(',', AttendanceType::values()),
            'status' => 'sometimes|in:' . implode(',', AttendanceStatus::values()),
            'work_mode' => 'sometimes|in:' . implode(',', WorkMode::values()),
            'group_by' => 'sometimes|in:employee,date,shift_type',
        ]);

        $query = Attendance::with('Employee')
            ->when($request->user()->isEmployee(), fn($q) => $q->where('user_id', $request->user()->id))
            ->filter($validated)
            ->groupedData($validated['group_by'] ?? null);

        return response()->json($query->paginate(15));
    }

    public function create(Request $request)
    {
        
        $currentShiftType = ShiftType::getCurrentShiftType();
        $currentAttendanceType = AttendanceType::getCurrentAttendanceType();
        $employee = $request->user()->employee()->get()->first();

        return view('attendance.index', [
            'employee' => $employee,
            'departments' => Department::options(),
            'departmentTeams' => DepartmentTeam::options(),
            'employmentTypes' => EmploymentType::options(),
            'workModes' => WorkMode::options(),
            'attendanceTypes' => AttendanceType::options(),
            'currentAttendanceType' => $currentAttendanceType == null ? null : $currentAttendanceType->value,
            'shiftTypes' => ShiftType::options(),
            'currentShiftType' => $currentShiftType == null ? null : $currentShiftType->value,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_type' => 'required|in:' . implode(',', ShiftType::values()),
            'type' => 'required|in:' . implode(',', AttendanceType::values()),
            'work_mode' => 'required|in:' . implode(',', WorkMode::values()),
            'screenshot_workstation_selfie' => 'required|image|mimes:jpg,png|max:1502',
            'screenshot_cgc_chat' => 'required|image|mimes:jpg,png|max:1502',
            'screenshot_department_chat' => 'required|image|mimes:jpg,png|max:1502',
            'screenshot_team_chat' => 'required|image|mimes:jpg,png|max:1502',
            'screenshot_group_chat' => 'required|image|mimes:jpg,png|max:1502',
        ]);

        $paths = $this->uploadAndGetPaths($request);

        $attendance = Attendance::factory()->create([
            ...$validated,
            ...$paths,
            'time' => now()->format('H:i'),
            'date' => now()->toDateString()
        ]);

        return response()->json($attendance, 201);
    }

    private function uploadAndGetPaths(Request $request): array
    {
        $selfie = $request->file('screenshot_workstation_selfie');
        $cgcChat = $request->file('screenshot_cgc_chat');
        $deptChat = $request->file('screenshot_department_chat');
        $teamChat = $request->file('screenshot_team_chat');
        $groupChat = $request->file('screenshot_group_chat');

        $selfiePath = $selfie->store('proofs');
        $cgcChatPath = $cgcChat->store('proofs');
        $deptChatPath = $deptChat->store('proofs');
        $teamChatPath = $teamChat->store('proofs');
        $groupChatPath = $groupChat->store('proofs');

        return [
            'screenshot_workstation_selfie' => $selfiePath,
            'screenshot_cgc_chat' => $cgcChatPath,
            'screenshot_department_chat' => $deptChatPath,
            'screenshot_team_chat' => $teamChatPath,
            'screenshot_group_chat' => $groupChatPath,
        ];
    }

    public function show(Request $request, string $id)
    {
        $attendance = Attendance::where('id', '=', $id)->first() ?? abort(404);

        if ($request->user()->cannot('view', $attendance)) abort(403);

        return response()->json($attendance->load('employee'));
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