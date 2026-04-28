<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectTodoController extends Controller
{
    public function __invoke($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('view', $project);

        return view('ampp.projects.show.todos', [
            'project' => $project
        ]);
    }
}
