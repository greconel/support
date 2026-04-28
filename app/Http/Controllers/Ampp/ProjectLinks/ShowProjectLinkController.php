<?php

namespace App\Http\Controllers\Ampp\ProjectLinks;

use App\Http\Controllers\Controller;
use App\Models\Project;

use function view;

class ShowProjectLinkController extends Controller
{
    public function __invoke($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('view', $project);

        return view('ampp.projects.show.links', [
            'project' => $project
        ]);
    }
}
