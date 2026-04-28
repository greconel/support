<?php

namespace App\Jobs\DataImport;

use App\Models\TimeRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportTimeRegistrationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $timeRegistrations = DB::connection('old_ampp')->table('time_registrations')->get();

        foreach ($timeRegistrations as $timeRegistration) {
            TimeRegistration::unguard();

            TimeRegistration::create([
                'id' => $timeRegistration->id,
                'user_id' => $timeRegistration->user_id,
                'project_id' => $timeRegistration->project_id,
                'start' => $timeRegistration->startDateTime,
                'end' => $timeRegistration->endDateTime,
                'tags' => null,
                'description' => $timeRegistration->description,
                'is_billable' => true,
                'is_billed' => true,
                'created_at' => $timeRegistration->created_at,
                'updated_at' => $timeRegistration->updated_at
            ]);

            TimeRegistration::reguard();
        }
    }
}
