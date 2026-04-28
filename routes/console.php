<?php

use App\Jobs\CheckOverdueSchedulesJob;
use App\Jobs\ImportReleasesJob;
use App\Jobs\PingAllImplementationsJob;
use App\Jobs\PruneBeaconDataJob;
use App\Jobs\RefreshLaravelLogJob;
use App\Jobs\SplitMidnightTimeRegistrationsJob;
use Illuminate\Support\Facades\Schedule;

Schedule::command('passport:purge')->hourly();

Schedule::job(new RefreshLaravelLogJob)->monthly();

Schedule::job(new SplitMidnightTimeRegistrationsJob)->dailyAt('00:00');

Schedule::job(new ImportReleasesJob)->daily();

// Schedule::job(new CheckDealsDueDateJob);

Schedule::job(new PingAllImplementationsJob)->everyMinute();
Schedule::job(new CheckOverdueSchedulesJob)->everyFiveMinutes();
Schedule::job(new PruneBeaconDataJob)->daily();

Schedule::command('mail:process')->everyMinute()->withoutOverlapping();
Schedule::command('motion:sync')->everyFiveMinutes();