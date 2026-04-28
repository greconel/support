<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectEmailController extends Controller
{
    public function __invoke($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('view', $project);
        $this->authorize('emails', $project);

        return view('ampp.projects.show.emails', [
            'project' => $project
        ]);
    }
}
