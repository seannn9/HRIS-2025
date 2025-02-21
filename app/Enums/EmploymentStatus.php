<?php

namespace App\Enums;

enum EmploymentStatus: string
{
    case REGULAR = 'regular';
    case PROBATIONARY = 'probationary';
    case CONTRACTUAL = 'contractual';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}