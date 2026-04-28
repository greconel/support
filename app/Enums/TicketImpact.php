<?php

namespace App\Enums;

enum TicketImpact: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    public function label(): string
    {
        return match ($this){
            self::Low => __('Low'),
            self::Medium => __('Medium'),
            self::High => __('High'),
        };
    }

    public function color(): string
    {
        return match ($this){
            self::Low => 'secondary',
            self::Medium => 'warning',
            self::High => 'danger',
        };
    }

    public function motionPriority(): string
    {
        return match ($this){
            self::Low => 'MEDIUM',
            self::Medium => 'HIGH',
            self::High => 'ASAP',
        };
    }
}
