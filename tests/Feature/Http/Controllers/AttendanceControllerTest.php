<?php

use App\Enums\AttendanceType;
use App\Enums\ShiftType;
use App\Enums\UserRole;
use App\Enums\WorkMode;
use App\Models\Attendance;
use App\Models\User;

describe('Attendance Controller as Admin', function () {
    beforeEach(function () {
        $this->user = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $this->actingAs($this->user);
    });

    it('can filter attendances by date and type', function () {
        Attendance::factory()->create(['user_id' => $this->user->id, 'date' => '2023-01-01', 'type' => AttendanceType::TIME_IN]);
        Attendance::factory()->create(['user_id' => $this->user->id, 'date' => '2023-01-02', 'type' => AttendanceType::TIME_OUT]);

        $response = $this->getJson('/api/attendance/list?date_from=2023-01-01&date_to=2023-01-01&type=time_in');
        
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    });

    it('can create new attendance record', function () {
        $response = $this->postJson('/api/attendance/create', [
            'user_id' => $this->user->id,
            'date' => now()->format('Y-m-d'),
            'shift_type' => ShiftType::MORNING->value,
            'type' => AttendanceType::TIME_IN->value,
            'time' => '09:00',
            'work_mode' => WorkMode::REMOTE->value,
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
        $attendance = Attendance::factory()->create();
        $newTime = '10:00';

        $response = $this->patchJson("/api/attendance/{$attendance->id}", [
            'time' => $newTime
        ]);

        $response->assertOk();
        expect($attendance->fresh()->time->format('H:i'))->toBe($newTime);
    });

    it('can delete attendance record', function () {
        $attendance = Attendance::factory()->create();
        
        $response = $this->deleteJson("/api/attendance/{$attendance->id}");
        
        $response->assertNoContent();
        $this->assertDatabaseMissing('attendances', ['id' => $attendance->id]);
    });

    it('can group data by user', function () {
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'date' => '2023-01-01',
            'type' => AttendanceType::TIME_IN,
            'time' => '09:00'
        ]);
        Attendance::factory()->create([
            'user_id' => $this->user->id,
            'date' => '2023-01-01',
            'type' => AttendanceType::TIME_OUT,
            'time' => '17:00'
        ]);

        $response = $this->getJson('/api/attendance/list?group_by=user');
        
        $response
            ->assertOk()
            ->assertJsonStructure(['data' => [['user_id', 'total_entries']]]);
    });
});

describe('Attendance Controller as Employee', function () {
    beforeEach(function () {
        $this->user = User::factory()->create(['role' => UserRole::EMPLOYEE->value]);
        $this->actingAs($this->user);

        // Second user for employee error testing
        User::factory()->create(['id' => $this->user->id + 1, 'role' => UserRole::EMPLOYEE->value]);
    });

    it('cannot show attendance record', function () {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id + 1]);
        
        $response = $this->getJson("/api/attendance/{$attendance->id}");
        
        $response->assertForbidden();
    });

    it('cannot update attendance record', function () {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id + 1]);
        $newTime = '10:00';

        $response = $this->patchJson("/api/attendance/{$attendance->id}", [
            'time' => $newTime
        ]);

        $response->assertForbidden();
    });

    it('cannot delete own attendance record', function () {
        $attendance = Attendance::factory()->create();
            
        $response = $this->deleteJson("/api/attendance/{$attendance->id}");
        
        $response->assertForbidden();
    });
});
