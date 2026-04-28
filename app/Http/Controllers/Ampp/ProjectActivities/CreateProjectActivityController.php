<?php

namespace App\Http\Controllers\Ampp\ProjectActivities;

use App\Models\Project;
use App\Models\ProjectActivity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateProjectActivityController
{
    use AuthorizesRequests;

    public function __invoke(Project $project)
    {
        $this->authorize('create', ProjectActivity::class);

        return view('ampp.projectActivities.create', compact('project'));
    }
}
