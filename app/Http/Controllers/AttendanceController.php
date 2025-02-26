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
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Employee;
use App\Services\AttendancePhotoService;
use App\Services\EmployeeAttendanceStatusService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected AttendancePhotoService $photoService;
    protected EmployeeAttendanceStatusService $employeeService;

    public function __construct(
        AttendancePhotoService $photoService,
        EmployeeAttendanceStatusService $employeeService
    ) {
        $this->photoService = $photoService;
        $this->employeeService = $employeeService;
    }
    
    public function index(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'sometimes|date|before_or_equal:date_to',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
            'employee_id' => 'sometimes|exists:employees,id',
            'shift_type' => 'sometimes|in:' . implode(',', ShiftType::values()),
            'type' => 'sometimes|in:' . implode(',', AttendanceType::values()),
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

    public function store(StoreAttendanceRequest $request)
    {
        $validated = $request->validated();
        $dateToday = now();
        $employee = $request->user()->employee()->get()->first();
        
        $paths = $this->photoService->uploadProofs(
            $request->allFiles(),
            $employee,
            $dateToday,
            $validated['type'],
            $validated['shift_type'],
        );

        $attendance = Attendance::create([
            ...$validated,
            ...$paths,
            'date' => $dateToday->toDateString()
        ]);

        $status = AttendanceStatus::PRESENT;
        $statusUpdated = $this->employeeService->updateAttendanceStatus($employee, $status);

        if (!$statusUpdated) {
            // Log the failure but continue (don't fail the whole request)
            // You might want to add this to the response
        }

        return response()->json($attendance, 201);
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
            'work_mode' => 'sometimes|in:' . implode(',', WorkMode::values()),
            'selfie_path' => 'sometimes|string|max:255',
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