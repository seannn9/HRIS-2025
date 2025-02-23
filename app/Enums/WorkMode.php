<?php

namespace App\Enums;

enum WorkMode: string
{
    case ONSITE = 'onsite';
    case REMOTE = 'remote';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}