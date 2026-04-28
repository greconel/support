<?php

namespace App\Listeners;

use App\Events\AiCorrectionLogCreated;
use App\Jobs\UpdateAiSkillJob;
use App\Models\AiCorrectionLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SkillUpdateListener
{
    public function handle(AiCorrectionLogCreated $event): void
    {
        if (! config('helpdesk.ai.enabled', true)) {
            return;
        }

        $threshold = (int) config('helpdesk.ai.skill_update_threshold', 5);

        $pending = AiCorrectionLog::query()
            ->where('processed', false)
            ->where('ignore_in_training', false)
            ->count();

        if ($pending < $threshold) {
            return;
        }

        $lockKey = 'helpdesk.ai.skill_update_dispatched';

        if (Cache::has($lockKey)) {
            return;
        }

        Cache::put($lockKey, true, now()->addMinutes(10));

        UpdateAiSkillJob::dispatch();

        Log::info("SkillUpdateListener: job gedispatcht na {$pending} correcties.");
    }
}
