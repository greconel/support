<?php

namespace App\Enums;

enum QuotationStatus: int
{
    case Draft = 0;
    case Sent = 1;
    case Reminder = 2;
    case Accepted = 3;
    case Rejected = 4;

    public function label(): string
    {
        return match ($this){
            self::Draft => __('Draft'),
            self::Sent => __('Sent'),
            self::Reminder => __('Reminder'),
            self::Accepted => __('Accepted'),
            self::Rejected => __('Rejected'),
        };
    }

    public function progress(): int
    {
        return match ($this){
            self::Draft => 0,
            self::Sent => 33,
            self::Reminder => 66,
            self::Accepted, self::Rejected => 100,
        };
    }

    public function color(): string
    {
        return match ($this){
            self::Draft, self::Sent => 'primary',
            self::Reminder => 'warning',
            self::Accepted => 'success',
            self::Rejected => 'danger',
        };
    }
}
