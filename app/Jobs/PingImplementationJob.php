<?php

namespace App\Jobs;

use App\Models\Implementation;
use App\Services\HeartbeatService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PingImplementationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Implementation $implementation
    ) {}

    public function handle(HeartbeatService $heartbeatService): void
    {
        $heartbeatService->ping($this->implementation);
    }
}
