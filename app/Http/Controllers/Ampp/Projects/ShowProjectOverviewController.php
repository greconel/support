<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\DataTables\Ampp\ProjectTimeRegistrationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Traits\TimeConversionTrait;
use Illuminate\Http\Request;

class ShowProjectOverviewController extends Controller
{
    use TimeConversionTrait;

    public function __invoke(Request $request, $id, ProjectTimeRegistrationDataTable $dataTable)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('view', $project);

        $users = $project->users;

        $users
            ->each(
                fn(User $user) => $user->total_hours = $this->secondsToHoursAndMinutes(
                    $user->timeRegistrations()->where('project_id', $project->id)->sum('total_time_in_seconds')
                )
            )
        ;

        return view('ampp.projects.show.overview', [
            'project' => $project,
            'users' => $users
        ]);
    }
}
