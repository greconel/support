<?php

namespace App\Jobs;

use App\Models\Implementation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PingAllImplementationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Implementation::query()
            ->whereNotNull('app_url')
            ->whereNull('deleted_at')
            ->each(function (Implementation $implementation) {
                PingImplementationJob::dispatch($implementation);
            });
    }
}
