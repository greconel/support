<?php

namespace App\Enums;

enum ScheduleExecutionStatus: string
{
    case Started = 'started';
    case Completed = 'completed';
    case Failed = 'failed';
    case Crashed = 'crashed';

    public function label(): string
    {
        return match ($this) {
            self::Started => __('Started'),
            self::Completed => __('Completed'),
            self::Failed => __('Failed'),
            self::Crashed => __('Crashed'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Started => 'info',
            self::Completed => 'success',
            self::Failed => 'danger',
            self::Crashed => 'danger',
        };
    }
}
