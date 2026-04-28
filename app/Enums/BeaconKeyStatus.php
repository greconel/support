<?php

namespace App\Enums;

enum BeaconKeyStatus: string
{
    case Unclaimed = 'unclaimed';
    case Claimed = 'claimed';
    case Disabled = 'disabled';

    public function label(): string
    {
        return match ($this) {
            self::Unclaimed => __('Unclaimed'),
            self::Claimed => __('Claimed'),
            self::Disabled => __('Disabled'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Unclaimed => 'warning',
            self::Claimed => 'success',
            self::Disabled => 'secondary',
        };
    }
}
