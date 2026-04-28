<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LeaveProjectController
{
    use AuthorizesRequests;

    public function __invoke(Project $project)
    {
        $this->authorize('view', $project);

        $project->users()->detach(auth()->user());

        session()->flash('success', __('You are no longer part of :project', ['project' => $project->name]));

        return back();
    }
}
