<?php

namespace App\Enums;

enum TicketSource: string
{
    case Web = 'web';
    case Email = 'email';
    case Agent = 'agent';

    public function label(): string
    {
        return match ($this){
            self::Web => __('Web'),
            self::Email => __('Email'),
            self::Agent => __('Agent'),
        };
    }
}
