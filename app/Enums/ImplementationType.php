<?php

namespace App\Enums;

enum ImplementationType: string
{
    case Beacon = 'beacon';
    case Manual = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::Beacon => __('Beacon'),
            self::Manual => __('Manual'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Beacon => 'primary',
            self::Manual => 'info',
        };
    }
}
