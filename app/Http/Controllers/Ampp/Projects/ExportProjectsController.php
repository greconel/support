<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Exports\ProjectExport;
use App\Http\Controllers\Controller;
use App\Models\Project;

class ExportProjectsController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Project::class);

        return (new ProjectExport(auth()->user()))->download('projects.xlsx');
    }
}
