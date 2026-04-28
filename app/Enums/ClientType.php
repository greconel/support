<?php

namespace App\Enums;

enum ClientType: string
{
    case Client = 'client';

    case Lead = 'lead';

    case Bms = 'bms';

    public function label(): string
    {
        return match ($this){
            self::Client => __('Client'),
            self::Lead => __('Lead'),
            self::Bms => __('BMS'),
        };
    }

    public function color(): string
    {
        return match ($this){
            self::Client => 'green-600',
            self::Lead => 'yellow-600',
            self::Bms => 'blue-500',
        };
    }
}
