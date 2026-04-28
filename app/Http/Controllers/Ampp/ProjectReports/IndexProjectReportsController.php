<?php

namespace App\Http\Controllers\Ampp\ProjectReports;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexProjectReportsController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('reports', Project::class);

        return view('ampp.projectReports.index');
    }
}
