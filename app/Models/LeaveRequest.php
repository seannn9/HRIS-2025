<?php

namespace App\Models;

use App\Enums\LeaveStatus;
use App\Enums\LeaveType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    /** @use HasFactory<\Database\Factories\LeaveRequestFactory> */
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'leave_type' => LeaveType::class,
        'status' => LeaveStatus::class,
        'shift_covered' => 'array',
    ];
    
    protected $attributes = [
        'status' => LeaveStatus::PENDING->value
    ];

    protected $fillable = [
        'user_id', 'leave_type', 'start_date', 'end_date',
        'reason', 'status', 'ticket_number', 'shift_covered'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['leave_type'] ?? null, fn($q, $type) => 
                $q->where('leave_type', $type))
            ->when($filters['status'] ?? null, fn($q, $status) => 
                $q->where('status', $status))
            ->when($filters['start_date'] ?? null, fn($q, $date) => 
                $q->where('start_date', '>=', $date))
            ->when($filters['end_date'] ?? null, fn($q, $date) => 
                $q->where('end_date', '<=', $date))
            ->when($filters['user_id'] ?? null, fn($q, $userId) => 
                $q->where('user_id', $userId));
    }

    public function isPending() {
        return $this->status == LeaveStatus::PENDING;
    }

    public function isRejected() {
        return $this->status == LeaveStatus::REJECTED;
    }

    public function isApproved() {
        return $this->status == LeaveStatus::APPROVED;
    }
}
