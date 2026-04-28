<?php

namespace App\Http\Controllers\Ampp\ProjectActivities;

use App\Models\Project;
use App\Models\ProjectActivity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EditProjectActivityController
{
    use AuthorizesRequests;

    public function __invoke(Project $project, ProjectActivity $projectActivity)
    {
        $this->authorize('update', $projectActivity);

        return view('ampp.projectActivities.edit', compact('project', 'projectActivity'));
    }
}
