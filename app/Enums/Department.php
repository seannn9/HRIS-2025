<?php

namespace App\Enums;

enum Department: string
{
    case MANAGEMENT = 'management';
    case DIGITAL_OPERATIONS = 'digital operations';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}