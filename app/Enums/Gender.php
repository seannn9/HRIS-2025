<?php

namespace App\Enums;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
    
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    public static function getLabel(self $case): string
    {
        return ucfirst($case->value);
    }
}