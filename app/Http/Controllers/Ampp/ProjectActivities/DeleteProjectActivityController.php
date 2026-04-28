<?php

namespace App\Http\Controllers\Ampp\ProjectActivities;

use App\Models\ProjectActivity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DeleteProjectActivityController
{
    use AuthorizesRequests;

    public function __invoke(ProjectActivity $projectActivity)
    {
        $this->authorize('delete', $projectActivity);

        $projectActivity->delete();

        session()->flash('success', __('Activity is deleted'));

        return redirect()->action(IndexProjectActivityController::class, $projectActivity->project);
    }
}
