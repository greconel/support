<?php

namespace App\Http\Controllers\Ampp\TimeRegistrations;

use App\Exports\TimeRegistrationExport;
use App\Http\Controllers\Controller;
use App\Models\Project;

class ExportTimeRegistrationsForProjectController extends Controller
{
    public function __invoke(Project $project)
    {
        $this->authorize('view', $project);

        return (new TimeRegistrationExport(project: $project))->download('time_registrations.xlsx');
    }
}
