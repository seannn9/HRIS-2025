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
    
    public static function getLabel(ShiftType $case) {
        switch ($case) {
            case self::MORNING:
                return "Morning";
            case self::AFTERNOON:
                return "Afternoon";
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

    
    public static function getCurrentShiftType(): ?ShiftType {
        $MORNING_START = '74500';
        $MORNING_END = '123000';
        $NOON_START = '124500';
        $NOON_END = '173000';

        $now = date("His");

        if($now >= $NOON_START && $now <= $NOON_END){
            return ShiftType::AFTERNOON;
        } else if ($now >= $MORNING_START && $now <= $MORNING_END) {
            return ShiftType::MORNING;
        }

        return null;
    }
}