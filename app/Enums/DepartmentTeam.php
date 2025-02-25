<?php

namespace App\Enums;

enum DepartmentTeam: string
{
    case CORPORATE_SERVICES = 'corporate_services';
    case CLIENT_SERVICES = 'client_services';
    case CREATIVE_MULTIMEDIA = 'createive_mm';
    case WEB_MOBILE_DEVELOPMENT = 'webdev';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getLabel(DepartmentTeam $case) {
        switch ($case) {
            case self::CORPORATE_SERVICES:
                return "Corporate services";
            case self::CLIENT_SERVICES:
                return "Client services";
            case self::CREATIVE_MULTIMEDIA:
                return "Creative services";
            case self::WEB_MOBILE_DEVELOPMENT:
                return "Web & Mobile devlopment services";
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