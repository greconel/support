<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class RestoreProjectController extends Controller
{
    public function __invoke($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('delete', $project);

        $project->restore();

        session()->flash('success', __('Project restored to its full glory!'));

        return redirect()->back();
    }
}
