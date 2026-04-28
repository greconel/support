<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case BankTransfer = 'bank_transfer';
    case Cash = 'cash';
    case CreditCard = 'credit_card';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::BankTransfer => __('Bank transfer'),
            self::Cash => __('Cash'),
            self::CreditCard => __('Credit card'),
            self::Other => __('Other'),
        };
    }
}
