<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Gender;
use App\Enums\EmploymentStatus;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        // 'employee_id',
        'birthdate',
        'gender',
        'contact_number',
        'address',
        'emergency_contact_name',
        'emergency_contact_number',
        'hire_date',
        'employment_status'
    ];

    protected $casts = [
        'gender' => Gender::class,
        'employment_status' => EmploymentStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
