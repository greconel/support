<?php

namespace App\Console\Commands;

use App\Actions\Releases\ImportReleasesAction;
use Illuminate\Console\Command;

class ImportReleasesCommand extends Command
{
    protected $signature = 'ampp:importReleases';

    protected $description = 'Import Github releases of current linked repository';

    public function handle(ImportReleasesAction $importReleasesAction)
    {
        $importReleasesAction->execute();
    }
}
