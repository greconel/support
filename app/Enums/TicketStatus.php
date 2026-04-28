<?php

namespace App\Enums;

enum TicketStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case OnHold = 'on_hold';
    case ToClose = 'to_close';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this){
            self::New => __('New'),
            self::InProgress => __('In progress'),
            self::OnHold => __('On hold'),
            self::ToClose => __('To close'),
            self::Closed => __('Closed'),
        };
    }

    public function color(): string
    {
        return match ($this){
            self::New => 'primary',
            self::InProgress => 'info',
            self::OnHold => 'warning',
            self::ToClose => 'secondary',
            self::Closed => 'success',
        };
    }

    public function isOpen(): bool
    {
        return $this !== self::Closed;
    }
}
