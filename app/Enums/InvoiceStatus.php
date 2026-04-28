<?php

namespace App\Enums;

enum InvoiceStatus: int
{
    case Draft = 0;
    case Sent = 1;
    case Reminder = 2;
    case PartiallyPaid = 4;
    case Paid = 3;

    public function label(): string
    {
        return match ($this){
            self::Draft => __('Draft'),
            self::Sent => __('Sent'),
            self::Reminder => __('Reminder'),
            self::PartiallyPaid => __('Partially paid'),
            self::Paid => __('Paid'),
        };
    }

    public function progress(): int
    {
        return match ($this){
            self::Draft => 0,
            self::Sent => 25,
            self::Reminder => 50,
            self::PartiallyPaid => 75,
            self::Paid => 100,
        };
    }

    public function color(): string
    {
        return match ($this){
            self::Draft => 'secondary',
            self::Sent => 'primary',
            self::Reminder => 'warning',
            self::PartiallyPaid => 'info',
            self::Paid => 'success',
        };
    }
}
