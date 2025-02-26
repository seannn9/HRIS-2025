<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeAttendanceStatusService
{
    /**
     * Update an employee's attendance status
     *
     * @param Employee $employee
     * @param AttendanceStatus $status
     * @return bool Whether the update was successful
     */
    public function updateAttendanceStatus(Employee $employee, AttendanceStatus $status): bool
    {
        try {
            return $employee->update(['attendance_status' => $status->value]);
        } catch (\Exception $e) {
            Log::error('Failed to update employee attendance status', [
                'employee_id' => $employee->id,
                'status' => $status->value,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}