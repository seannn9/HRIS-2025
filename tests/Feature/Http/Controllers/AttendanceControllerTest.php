<?php

use App\Enums\AttendanceType;
use App\Enums\ShiftType;
use App\Enums\UserRole;
use App\Enums\WorkMode;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;

describe('Attendance Controller as Admin', function () {
    beforeEach(function () {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->employee = Employee::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
    });

    it('can filter attendances by date and type', function () {
        Attendance::factory()->create(['employee_id' => $this->employee->id, 'type' => AttendanceType::TIME_IN]);
        Attendance::factory()->create(['employee_id' => $this->employee->id, 'type' => AttendanceType::TIME_OUT]);

        $response = $this->getJson('/api/attendance?date_from=2023-01-01&date_to=2023-01-01&type=time_in');
        
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    });

    it('can create new attendance record', function () {
        $response = $this->postJson('/api/attendance', [
            'employee_id' => $this->employee->id,
            'shift_type' => ShiftType::MORNING,
            'type' => AttendanceType::TIME_IN,
            'work_mode' => WorkMode::REMOTE,
        ]);

        $response->assertCreated();
        $this->assertDatabaseCount('attendances', 1);
    });

    it('can show attendance record', function () {
        $attendance = Attendance::factory()->create();
        
        $response = $this->getJson("/api/attendance/{$attendance->id}");
    
        $response
            ->assertOk()
            ->assertJsonPath('id', $attendance->id);
    });

    it('cannot show non-existing record', function () {
        $attendance = Attendance::factory()->create();
        
        $response = $this->getJson("/api/attendance/".$attendance->id + 1);
    
        $response-> assertNotFound();
    });

    it('can update attendance record', function () {
        $attendance = Attendance::factory()->create(['type' => AttendanceType::TIME_IN->value]);

        $response = $this->patchJson("/api/attendance/{$attendance->id}", [
            'type' => AttendanceType::TIME_OUT->value
        ]);

        $response->assertOk();
        expect($attendance->fresh()->type)->toBe(AttendanceType::TIME_OUT->value);
    });

    it('can delete attendance record', function () {
        $attendance = Attendance::factory()->create();
        
        $response = $this->deleteJson("/api/attendance/{$attendance->id}");
        
        $response->assertNoContent();
        $this->assertDatabaseMissing('attendances', ['id' => $attendance->id]);
    });

    it('can group data by user', function () {
        Attendance::factory()->create([
            'employee_id' => $this->employee->id,
            'type' => AttendanceType::TIME_IN
        ]);
        Attendance::factory()->create([
            'employee_id' => $this->employee->id,
            'type' => AttendanceType::TIME_OUT
        ]);

        $response = $this->getJson('/api/attendance?group_by=employee');
        
        $response
            ->assertOk()
            ->assertJsonStructure(['data' => [['employee_id', 'total_entries']]]);
    });
});

describe('Attendance Controller as Employee', function () {
    beforeEach(function () {
        $users = User::factory()->count(2)->create(['role' => UserRole::EMPLOYEE]);
        $this->actingAs($users->get(0));

        foreach ($users as $user) {
            Employee::factory()->create(['user_id' => $user->id]);
        }

        $this->employee = Employee::all()->get(0);
    });

    it('cannot show attendance record', function () {
        $attendance = Attendance::factory()->create(['employee_id' => $this->employee->id + 1]);
        
        $response = $this->getJson("/api/attendance/{$attendance->id}");
        
        $response->assertForbidden();
    });

    it('cannot update attendance record', function () {
        $attendance = Attendance::factory()->create(['type' => AttendanceType::TIME_IN->value]);

        $response = $this->patchJson("/api/attendance/{$attendance->id}", [
            'type' => AttendanceType::TIME_OUT->value
        ]);

        $response->assertForbidden();
    });

    it('cannot delete own attendance record', function () {
        $attendance = Attendance::factory()->create();
            
        $response = $this->deleteJson("/api/attendance/{$attendance->id}");
        
        $response->assertForbidden();
    });
});
