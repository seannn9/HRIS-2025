<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case HR = 'hr';
    case EMPLOYEE = 'employee';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isAdmin()
    {
        return $this == UserRole::ADMIN;
    }

    public function isHr()
    {
        return $this == UserRole::HR;
    }

    public function isEmployee()
    {
        return $this == UserRole::EMPLOYEE;
    }
}