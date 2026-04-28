<?php

namespace App\Jobs;

use App\Models\ImplementationError;
use App\Models\ImplementationHeartbeat;
use App\Models\ImplementationSnapshot;
use App\Models\ScheduleExecution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PruneBeaconDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $retentionDays = (int) config('beacon.retention_days', 30);
        $cutoff = now()->subDays($retentionDays);

        ImplementationHeartbeat::where('created_at', '<', $cutoff)->delete();
        ScheduleExecution::where('created_at', '<', $cutoff)->delete();
        ImplementationError::where('created_at', '<', $cutoff)->delete();

        // Keep one snapshot per day, remove intra-day duplicates beyond retention
        ImplementationSnapshot::where('created_at', '<', $cutoff)->delete();
    }
}
