<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectNoteController extends Controller
{
    public function __invoke($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('view', $project);

        return view('ampp.projects.show.notes', [
            'project' => $project
        ]);
    }
}
