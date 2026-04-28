<?php

namespace App\Jobs;

use App\Models\ImplementationSchedule;
use App\Models\User;
use App\Notifications\Beacon\ScheduleOverdueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckOverdueSchedulesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $schedules = ImplementationSchedule::with('implementation')
            ->where('is_active', true)
            ->whereHas('implementation', fn ($q) => $q->whereNull('deleted_at'))
            ->get();

        $admins = null;

        foreach ($schedules as $schedule) {
            if (!$schedule->is_overdue) {
                continue;
            }

            // Only notify once per overdue period — check if we already notified
            // by looking for a recent overdue notification (within the schedule's interval)
            $alreadyNotified = $this->wasRecentlyNotified($schedule);

            if ($alreadyNotified) {
                continue;
            }

            // Cache the admin query
            if ($admins === null) {
                $admins = User::whereRelation('roles', 'name', '=', 'super admin')->get();
            }

            if ($admins->isEmpty()) {
                return;
            }

            Log::warning('Beacon: schedule overdue', [
                'implementation' => $schedule->implementation->name,
                'command' => $schedule->command,
                'last_finished_at' => $schedule->last_finished_at,
            ]);

            Notification::send($admins, new ScheduleOverdueNotification($schedule));

            // Mark as notified by touching a timestamp
            $schedule->update(['last_overdue_notified_at' => now()]);
        }
    }

    private function wasRecentlyNotified(ImplementationSchedule $schedule): bool
    {
        if (!$schedule->last_overdue_notified_at) {
            return false;
        }

        // Don't re-notify within the same overdue period (at least 1 hour between notifications)
        return $schedule->last_overdue_notified_at->greaterThan(now()->subHour());
    }
}
