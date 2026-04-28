<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\DataTables\Ampp\ProjectDataTable;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Traits\TimeConversionTrait;
use Illuminate\Http\Request;

class IndexProjectController extends Controller
{
    use TimeConversionTrait;

    public function __invoke(ProjectDataTable $dataTable, Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $totalSeconds = Project::withTrashed()->get()->sum(function (Project $project){
            return $project->timeRegistrations()->sum('total_time_in_seconds');
        });

        $totalHours = $this->secondsToHoursAndMinutes($totalSeconds);

        $users = User::pluck('name', 'id')->prepend('Team', 'team')->all();

        return $dataTable
            ->with([
                'category' => $request->query('category'),
                'archive' => $request->query('archive'),
                'assignee' => $request->query('assignee')
            ])
            ->render('ampp.projects.index', [
                'archiveCount' => Project::onlyTrashed()->count(),
                'assignee' => $request->query('assignee'),
                'users' => $users,
                'totalHours' => $totalHours,
                'totalProjects' => Project::withTrashed()->count(),
                'totalTodos' => Project::withTrashed()->withCount('todos')->get()->sum('todos_count'),
            ])
        ;
    }
}
