<?php

namespace App\Jobs;

use App\Actions\Releases\ImportReleasesAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportReleasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ImportReleasesAction $importReleasesAction)
    {
        $importReleasesAction->execute();
    }
}
