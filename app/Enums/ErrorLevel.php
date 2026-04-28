<?php

namespace App\Enums;

enum ErrorLevel: string
{
    case Error = 'error';
    case Critical = 'critical';
    case Emergency = 'emergency';

    public function label(): string
    {
        return match ($this) {
            self::Error => __('Error'),
            self::Critical => __('Critical'),
            self::Emergency => __('Emergency'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Error => 'warning',
            self::Critical => 'danger',
            self::Emergency => 'danger',
        };
    }
}
