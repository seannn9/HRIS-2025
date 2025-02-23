<?php

use App\Enums\AttendanceType;
use App\Models\Attendance;
use App\Models\User;

describe('Attendance Controller', function () {
    beforeEach(function () {
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->user);
    });

    it('can filter attendances by date and type', function () {
        Attendance::factory()->create(['date' => '2023-01-01', 'type' => AttendanceType::TIME_IN]);
        Attendance::factory()->create(['date' => '2023-01-02', 'type' => AttendanceType::TIME_OUT]);

        $response = $this->getJson('/api/attendances?date_from=2023-01-01&date_to=2023-01-01&type=time_in');
        
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    });

    it('can create new attendance record', function () {
        $response = $this->postJson('/api/attendances', [
            'date' => now()->format('Y-m-d'),
            'shift_type' => 'morning',
            'type' => 'time_in',
            'time' => '09:00',
        ]);

        $response->assertCreated();
        $this->assertDatabaseCount('attendances', 1);
    });

    it('can show attendance record', function () {
        $attendance = Attendance::factory()->create();
        
        $response = $this->getJson("/api/attendances/{$attendance->id}");
        
        $response
            ->assertOk()
            ->assertJsonPath('data.id', $attendance->id);
    });

    it('can update attendance record', function () {
        $attendance = Attendance::factory()->create();
        $newTime = '10:00';

        $response = $this->putJson("/api/attendances/{$attendance->id}", [
            'time' => $newTime
        ]);

        $response->assertOk();
        expect($attendance->fresh()->time->format('H:i'))->toBe($newTime);
    });

    it('can delete attendance record', function () {
        $attendance = Attendance::factory()->create();
        
        $response = $this->deleteJson("/api/attendances/{$attendance->id}");
        
        $response->assertNoContent();
        $this->assertSoftDeleted($attendance);
    });

    it('can group data by user', function () {
        $user = User::factory()->create();
        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2023-01-01',
            'type' => AttendanceType::TIME_IN,
            'time' => '09:00'
        ]);
        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2023-01-01',
            'type' => AttendanceType::TIME_OUT,
            'time' => '17:00'
        ]);

        $response = $this->getJson('/api/attendances?group_by=user');
        
        $response
            ->assertOk()
            ->assertJsonStructure(['data' => [['user_id', 'total_entries']]]);
    });
});
