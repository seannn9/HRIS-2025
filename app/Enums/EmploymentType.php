<?php

namespace App\Enums;

enum EmploymentType: string
{
    case REGULAR = 'regular';
    case K12_WORK_IMMERSION = 'work_immersion';
    case INTERNSHIP = 'internship';
    case APPRENTICESHIP = 'apprenticeship';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}