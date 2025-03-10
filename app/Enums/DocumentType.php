<?php

namespace App\Enums;

enum DocumentType: string
{
    case RESUME = 'resume';
    case ID = 'id';
    case CONTRACT = 'contract';
    case CLEARANCE = 'clearance';
    case OTHER = 'other';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    public static function getLabel(self $case): string
    {
        return ucfirst($case->value);
    }

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = self::getLabel($case);
            return $carry;
        }, []);
    }
}