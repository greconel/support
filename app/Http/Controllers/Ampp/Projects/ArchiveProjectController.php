<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ArchiveProjectController extends Controller
{
    public function __invoke(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        session()->flash('success', __('Project is now archived'));

        return redirect()->action(IndexProjectController::class);
    }
}
