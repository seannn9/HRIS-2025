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
    
    public static function getLabel(EmploymentType $case) {
        switch ($case) {
            case self::REGULAR:
                return "Regular";
            case self::K12_WORK_IMMERSION:
                return "K12 Work Immersion";
            case self::INTERNSHIP:
                return "College Internship";
            case self::APPRENTICESHIP:
                return "Graduate Apprenticeship";
        }
    }

    public static function options()
    {
        $dict = array();
        foreach (self::cases() as $case) {
            $dict[$case->value] = self::getLabel($case);
        }

        return $dict;
    }
}