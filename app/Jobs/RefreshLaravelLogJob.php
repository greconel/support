<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Exception\FileNotFoundException;

class RefreshLaravelLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $nameToArchive = 'laravel_' . now()->format('Y_m') . '.log';

        if (! Storage::disk('logs')->exists('laravel.log')) {
            Throw new FileNotFoundException();
        }

        Storage::disk('logs')->move('laravel.log', "archive/$nameToArchive");

        Storage::disk('logs')->put('laravel.log', null);
    }
}
