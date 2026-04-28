<?php

namespace App\Http\Controllers\Ampp\TimeRegistrations;

use App\Exports\TimeRegistrationExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\TimeRegistrations\ExportTimeRegistrationsRequest;
use App\Models\Project;
use App\Models\User;

class ExportTimeRegistrationsController extends Controller
{
    public function __invoke(ExportTimeRegistrationsRequest $request)
    {
        $user = $request->has('user')
            ? User::find($request->input('user'))
            : auth()->user();

        $project = Project::find($request->input('project'));

        return (new TimeRegistrationExport($user, $project))->download('time_registrations.xlsx');
    }
}
