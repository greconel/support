<?php

namespace App\Jobs;

use App\Models\TimeRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SplitMidnightTimeRegistrationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $timeRegistrations = TimeRegistration::whereNull('end')->get();

        foreach ($timeRegistrations as $timeRegistration){
            $timeRegistration->update(['end' => $timeRegistration->start->endOfDay()]);

            $newTimeRegistration = $timeRegistration->replicate()->fill([
                'start' => now()->startOfDay(),
                'end' => null,
                'total_time_in_seconds' => null
            ]);

            $newTimeRegistration->save();
        }
    }
}
