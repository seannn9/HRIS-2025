<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;
use App\Enums\UserStatus;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'position',
        'status',
    ];

    protected $attributes = [
        'role' => UserRole::EMPLOYEE->value,
        'status' => UserStatus::ACTIVE->value,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role' => 'string',
        'status' => 'string',
        'role' => UserRole::class,
        'status' => UserStatus::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}