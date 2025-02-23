<?php

namespace App\Enums;

enum AttendanceType: string
{
    case TIME_IN = 'time_in';
    case TIME_OUT = 'time_out';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}