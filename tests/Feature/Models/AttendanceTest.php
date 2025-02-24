<?php

use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
use App\Enums\ShiftType;
use App\Enums\WorkMode;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

describe('Attendance Model', function () {
    beforeEach(function () {
        $this->attendance = Attendance::factory()->create(['type' => AttendanceType::TIME_OUT->value]);
    });

    it('belongs to a user', function () {
        expect($this->attendance->user)->toBeInstanceOf(User::class);
    });

    it('has correct cast attributes', function () {
        expect($this->attendance->getCasts())
            ->toMatchArray([
                'date' => 'date',
                'shift_type' => ShiftType::class,
                'type' => AttendanceType::class,
                'work_mode' => WorkMode::class,
                'status' => AttendanceStatus::class,
                'time' => 'datetime',
            ]);
    });

    it('applies filter scope correctly', function () {
        $attendance = Attendance::factory()->create([
            'date' => '2023-01-01',
            'type' => AttendanceType::TIME_IN
        ]);
        
        $request = new Request([
            'date_from' => '2023-01-01',
            'type' => AttendanceType::TIME_IN->value
        ]);
        $filtered = Attendance::filter($request->all())->get();

        expect($filtered)->toHaveCount(1)
            ->and($filtered->first()->id)->toBe($attendance->id);
    });

    it('applies grouped data scope correctly', function () {
        $user = User::factory()->create();
        Attendance::factory()->count(3)->create(['user_id' => $user->id]);
        
        $grouped = Attendance::groupedData('user')->get();
        
        expect($grouped)->toHaveCount(2)
            ->and($grouped->last()->total_entries)->toBe(3);
    });

    it('has unique ticket number', function () {
        $ticket = 'TICKET-12345';
        
        Attendance::factory()->create(['ticket_number' => $ticket]);
        
        expect(fn() => Attendance::factory()->create(['ticket_number' => $ticket]))
            ->toThrow(\Illuminate\Database\QueryException::class);
    });

    it('prevents duplicate entries for same user/date/type', function () {
        $data = [
            'id' => random_int(1000, 9506),
            'user_id' => $this->attendance->user()->get()->first()->id,
            'date' => '2023-01-01',
            'type' => AttendanceType::TIME_IN
        ];
        
        Attendance::factory()->create($data);
        
        expect(fn() => Attendance::factory()->create($data))
            ->toThrow(\Illuminate\Database\QueryException::class);
    });
});