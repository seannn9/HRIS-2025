<?php

namespace App\Enums;

enum ShiftType: string
{
    case MORNING = 'morning';
    case AFTERNOON = 'afternoon';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}