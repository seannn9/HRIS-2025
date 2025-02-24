<?php

namespace App\Models;

use App\Enums\ShiftType;
use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
use App\Enums\WorkMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function scopeFilter($query, array $filters)
    {
        return $query->when(isset($filters['date_from']), fn($q) => 
                $q->where('date', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn($q) => 
                $q->where('date', '<=', $filters['date_to']))
            ->when(isset($filters['user_id']), fn($q) => 
                $q->where('user_id', $filters['user_id']))
            ->when(isset($filters['shift_type']), fn($q) => 
                $q->where('shift_type', $filters['shift_type']))
            ->when(isset($filters['type']), fn($q) => 
                $q->where('type', $filters['type']))
            ->when(isset($filters['status']), fn($q) => 
                $q->where('status', $filters['status']))
            ->when(isset($filters['work_mode']), fn($q) => 
                $q->where('work_mode', $filters['work_mode']));
    }

    public function scopeGroupedData($query, ?string $groupBy)
    {
        // TODO: Improve this shit
        return match ($groupBy) {
            'user' => $query->select('user_id', DB::raw('COUNT(*) as total_entries'))
                ->groupBy('user_id'),
            'date' => $query->select('date', DB::raw('COUNT(*) as total_entries'))
                ->groupBy('date'),
            'shift_type' => $query->select('shift_type', DB::raw('COUNT(*) as total_entries'))
                ->groupBy('shift_type'),
            default => $query,
        };
    }
}
