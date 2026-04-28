<?php

namespace App\Http\Controllers\Ampp\ProjectActivities;

use App\Http\Requests\Ampp\ProjectActivities\ProjectActivityFormRequest;
use App\Models\Project;
use App\Models\ProjectActivity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StoreProjectActivityController
{
    use AuthorizesRequests;

    public function __invoke(Project $project, ProjectActivityFormRequest $request)
    {
        $this->authorize('create', ProjectActivity::class);

        $project->projectActivities()->create(array_merge(
            $request->validated(),
            ['is_active' => $request->boolean('is_active')]
        ));

        session()->flash('success', __('Activity successfully added.'));

        return redirect()->action(IndexProjectActivityController::class, $project);
    }
}
