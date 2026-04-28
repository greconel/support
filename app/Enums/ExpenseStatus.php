<?php

namespace App\Enums;

enum ExpenseStatus: int
{
    case Draft = 0;
    case DirectDebit = 1;
    case Paid = 2;

    public function label(): string
    {
        return match ($this){
            self::Draft => __('Draft'),
            self::DirectDebit => __('Direct debit'),
            self::Paid => __('Paid'),
        };
    }

    public function progress(): int
    {
        return match ($this){
            self::Draft => 0,
            self::DirectDebit => 100,
            self::Paid => 100,
        };
    }

    public function color(): string
    {
        return match ($this){
            self::Draft => 'primary',
            self::Paid, self::DirectDebit => 'success',
        };
    }
}
