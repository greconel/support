<?php

namespace App\Http\Controllers\Ampp\ProjectActivities;

use App\Models\Project;
use App\Models\ProjectActivity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IndexProjectActivityController
{
    use AuthorizesRequests;

    public function __invoke(Project $project)
    {
        $this->authorize('viewAny', ProjectActivity::class);

        $activities = $project->projectActivities->load('timeRegistrations');

        return view('ampp.projectActivities.index', compact('project', 'activities'));
    }
}
