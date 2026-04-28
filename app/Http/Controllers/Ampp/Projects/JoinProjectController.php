<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JoinProjectController
{
    use AuthorizesRequests;

    public function __invoke(Project $project)
    {
        $this->authorize('view', $project);

        abort_if($project->users->contains(auth()->user()), 403);

        $project->users()->attach(auth()->id());

        session()->flash('success', __('You are now part of :project', ['project' => $project->name]));

        return back();
    }
}
