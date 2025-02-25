<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Enums\{LeaveStatus, LeaveType};
use App\Models\Employee;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'sometimes|in:' . implode(',', LeaveType::values()),
            'status' => 'sometimes|in:' . implode(',', LeaveStatus::values()),
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'employee_id' => 'sometimes|exists:employees,id'
        ]);

        $query = LeaveRequest::with('Employee')
            ->filter($validated)
            ->when($request->user()->isEmployee(), fn($q) => $q->where('user_id', $request->user()->id));

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('create', LeaveRequest::class)) abort(403);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:' . implode(',', LeaveType::values()),
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'shift_covered' => 'sometimes|array',
            'shift_covered.*' => 'string|max:255',
        ]);

        $leave = LeaveRequest::factory()->create([
            ...$validated,
            'status' => LeaveStatus::PENDING
        ]);

        return response()->json($leave, 201);
    }

    public function show(Request $request, string $id)
    {
        $leave = LeaveRequest::where('id', '=', $id)->first() ?? abort(404);
        if ($request->user()->cannot('view', $leave)) abort(403);

        return response()->json($leave->load('user'));
    }

    public function update(Request $request, string $id)
    {
        if ($request->input('status') != null) abort(403);

        $leave = LeaveRequest::where('id', '=', $id)->first() ?? abort(404);
        if ($request->user()->cannot('update', $leave)) abort(403);

        $validated = $request->validate([
            'leave_type' => 'sometimes|in:' . implode(',', LeaveType::values()),
            'start_date' => 'sometimes|date|after_or_equal:today',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'reason' => 'sometimes|string|max:500',
            'shift_covered' => 'sometimes|array',
            'shift_covered.*' => 'string|max:255'
        ]);

        $leave->update($validated);
        return response()->json($leave);
    }

    public function destroy(Request $request, string $id)
    {
        $leave = LeaveRequest::where('id', '=', $id)->first() ?? abort(404);
        if ($request->user()->cannot('delete', $leave)) abort(403);;

        $leave->delete();
        return response()->noContent();
    }

    public function updateStatus(Request $request, string $id)
    {
        $leave = LeaveRequest::where('id', '=', $id)->first() ?? abort(404);
        if ($request->user()->cannot('updateStatus', $leave)) abort(403);

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', LeaveStatus::values()),
            'rejection_reason' => 'required_if:status,'.LeaveStatus::REJECTED->value.'|string|max:255'
        ]);

        $leave->update([
            'status' => $validated['status'],
            'reason' => $validated['rejection_reason'] ?? $leave->reason
        ]);

        return response()->json($leave);
    }
}
