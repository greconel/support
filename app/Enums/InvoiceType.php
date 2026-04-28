<?php

namespace App\Enums;

enum InvoiceType: string
{
    case Debit = 'debit';
    case Credit = 'credit';

    public function label(): string
    {
        return match ($this){
            self::Debit => __('Debit'),
            self::Credit => __('Credit'),
        };
    }
}
