<?php

namespace App\Models;

use App\Enums\ShiftType;
use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
use App\Enums\WorkMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    protected $casts = [
        'date' => 'date',
        'shift_type' => ShiftType::class,
        'type' => AttendanceType::class,
        'work_mode' => WorkMode::class,
        'status' => AttendanceStatus::class,
        'time' => 'datetime',
    ];

    protected $attributes = [
        'status' => AttendanceStatus::PRESENT->value,
        'work_mode' => WorkMode::ONSITE->value,
    ];

    protected $fillable = [
        'user_id',
        'date',
        'shift_type',
        'type',
        'time',
        'work_mode',
        'selfie_path',
        'status',
        'ticket_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
