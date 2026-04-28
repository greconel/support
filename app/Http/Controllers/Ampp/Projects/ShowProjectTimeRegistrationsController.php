<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\DataTables\Ampp\ProjectTimeRegistrationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectTimeRegistrationsController extends Controller
{
    public function __invoke($id, ProjectTimeRegistrationDataTable $dataTable)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('view', $project);

        return $dataTable
            ->with([
                'project_id' => $project->id,
                'users' => request()->input('users', $project->users->pluck('id')->toArray())
            ])
            ->render('ampp.projects.show.timeRegistrations', [
                'project' => $project
            ])
        ;
    }
}
