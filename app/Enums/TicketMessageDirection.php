<?php

namespace App\Enums;

enum TicketMessageDirection: string
{
    case Inbound = 'inbound';
    case Outbound = 'outbound';

    public function label(): string
    {
        return match ($this){
            self::Inbound => __('Inbound'),
            self::Outbound => __('Outbound'),
        };
    }
}
