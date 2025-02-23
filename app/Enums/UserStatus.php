<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    /**
     * Checks if user is active
     */
    public function isActive() {
        return $this == UserStatus::ACTIVE;
    }
}