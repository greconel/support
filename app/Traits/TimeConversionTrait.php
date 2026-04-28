<?php

namespace App\Traits;

trait TimeConversionTrait
{
    public function secondsToHoursAndMinutes(?int $seconds = 0): string
    {
        if (! $seconds){
            return "00:00";
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

        return "$hours:$minutes";
    }

    public function secondsToHours(?int $seconds = 0): int
    {
        if (! $seconds){
            return 0;
        }

        return ceil($seconds / 3600);
    }

    public function secondsToHoursFloat(?int $seconds = 0) : float {
        if (! $seconds){
            return 0;
        }

        return round((float)$seconds / 3600, 2);
    }
}
