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

    public static function getLabel(WorkMode $case) {
        switch ($case) {
            case self::ONSITE:
                return "On-site";
            case self::REMOTE:
                return "Remote";
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