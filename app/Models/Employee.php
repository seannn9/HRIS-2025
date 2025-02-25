<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Gender;
use App\Enums\EmploymentType;
use App\Enums\EmployeeStatus;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birthdate',
        'gender',
        'contact_number',
        'address',
        'emergency_contact_name',
        'emergency_contact_number',
        'hire_date',
        'employment_type',
        'department',
        'position',
        'status',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'employment_type' => EmploymentType::class,
        'status' => EmployeeStatus::class,
    ];

    protected $attributes = [
        'status' => EmployeeStatus::ACTIVE,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
