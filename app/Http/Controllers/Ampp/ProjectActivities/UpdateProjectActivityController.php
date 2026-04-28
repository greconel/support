<?php

namespace App\Http\Controllers\Ampp\ProjectActivities;

use App\Http\Requests\Ampp\ProjectActivities\ProjectActivityFormRequest;
use App\Models\Project;
use App\Models\ProjectActivity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UpdateProjectActivityController
{
    use AuthorizesRequests;

    public function __invoke(Project $project, ProjectActivity $projectActivity, ProjectActivityFormRequest $request)
    {
        $this->authorize('update', $projectActivity);

        $projectActivity->update(array_merge(
            $request->validated(),
            ['is_active' => $request->boolean('is_active')]
        ));

        session()->flash('success', __('Activity updated'));

        return redirect()->action(IndexProjectActivityController::class, $project);
    }
}
