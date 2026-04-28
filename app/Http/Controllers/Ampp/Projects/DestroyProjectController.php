<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class DestroyProjectController extends Controller
{
    public function __invoke($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('delete', $project);

        $project->forceDelete();

        session()->flash('success', __('Goodbye project! 😥'));

        return redirect()->action(IndexProjectController::class);
    }
}
