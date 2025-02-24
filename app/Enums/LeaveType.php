<?php

namespace App\Enums;

enum LeaveType: string
{
    case SICK = 'sick';
    case ACADEMIC = 'academic';
    case VACATION = 'vacation';
    case MATERNITY = 'maternity';
    case PATERNITY = 'paternity';
    case EMERGENCY = 'emergency';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}