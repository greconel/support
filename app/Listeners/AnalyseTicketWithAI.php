<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Jobs\AnalyseTicketJob;

class AnalyseTicketWithAI
{
    public function handle(TicketCreated $event): void
    {
        if (! config('helpdesk.ai.enabled', true)) {
            return;
        }

        AnalyseTicketJob::dispatch($event->ticket);
    }
}
