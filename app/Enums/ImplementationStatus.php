<?php

namespace App\Enums;

enum ImplementationStatus: string
{
    case Online = 'online';
    case Degraded = 'degraded';
    case Offline = 'offline';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::Online => __('Online'),
            self::Degraded => __('Degraded'),
            self::Offline => __('Offline'),
            self::Unknown => __('Unknown'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Online => 'success',
            self::Degraded => 'warning',
            self::Offline => 'danger',
            self::Unknown => 'secondary',
        };
    }
}
