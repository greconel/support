<?php

namespace App\Enums;

enum VariousTransactionCategory: int
{
    case Vat = 0;

    case Rent = 1;

    case Wages = 2;

    case BankCharges = 3;

    case Other = 4;

    case Transfer = 5;

    public function label(): string
    {
        return match ($this){
            self::Vat => __('VAT'),
            self::Rent => __('Rent'),
            self::Wages => __('Wages'),
            self::BankCharges => __('Banck charges'),
            self::Other => __('Other'),
            self::Transfer => __('Transfer'),
        };
    }
}
