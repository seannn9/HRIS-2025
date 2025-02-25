<?php

namespace App\Enums;

enum Department: string
{
    case MANAGEMENT = 'management';
    case DIGITAL_OPERATIONS = 'digital_operations';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getLabel(Department $case) {
        switch ($case) {
            case self::MANAGEMENT:
                return "Management";
            case self::DIGITAL_OPERATIONS:
                return "Digital Operations";
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