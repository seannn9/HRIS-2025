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

    public static function getLabel(self $case): string
    {
        return match ($case) {
            self::HR => "HR",
            default => ucfirst($case->value)
        };
    }
}