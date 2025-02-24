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
}