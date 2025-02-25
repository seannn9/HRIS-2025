<?php

namespace App\Enums;

enum AttendanceType: string
{
    case TIME_IN = 'time_in';
    case TIME_OUT = 'time_out';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    public static function getLabel(AttendanceType $case) {
        switch ($case) {
            case self::TIME_IN:
                return "Time In";
            case self::TIME_OUT:
                return "Time Out";
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

    public static function getCurrentAttendanceType(): ?AttendanceType {
        $MORNING_IN_START = '74500';
        $MORNING_IN_END = '83000';
        $MORNING_OUT_START = '120000';
        $MORNING_OUT_END = '123000';

        $NOON_IN_START = '124500';
        $NOON_IN_END = '130000';
        $NOON_OUT_START = '164500';
        $NOON_OUT_END = '173000';

        $now = date("His");

        $shouldTimeIn = ($now >= $MORNING_IN_START && $now <= $MORNING_IN_END)
            || ($now >= $NOON_IN_START && $now <= $NOON_IN_END);
        $shouldTimeOut = ($now >= $MORNING_OUT_START && $now <= $MORNING_OUT_END)
            || ($now >= $NOON_OUT_START && $now <= $NOON_OUT_END);

        if($shouldTimeIn){
            return AttendanceType::TIME_IN;
        } else if ($shouldTimeOut) {
            return AttendanceType::TIME_OUT;
        }

        return null;
    }
}