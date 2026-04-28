<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function queryWithHours(Request $request)
    {
        $this->validate($request, [
            'query' => ['nullable', 'string'],
            'month' => ['required', 'date_format:m/Y']
        ]);

        if ($request->input('query')) {
            $projects = Project::where('name', 'like', "%{$request->input('query')}%")
                ->orWhereRelation('client', function (Builder $q) use ($request) {
                    return $q->where('first_name', 'like', "%{$request->input('query')}%")
                        ->orWhere('last_name', 'like', "%{$request->input('query')}%");
                })
                ->get();
        }
        else{
            $projects = Project::all();
        }

        $projectsToReturn = collect();

        foreach ($projects as $project){
            $seconds = $project->timeRegistrations()
                ->whereMonth('start', Carbon::createFromFormat('m/Y', $request->input('month')))
                ->sum('total_time_in_seconds');

            $projectsToReturn->push([
                'id' => $project->id,
                'name' => $project->name_with_client,
                'hours' => round(CarbonInterval::seconds($seconds)->totalHours),
            ]);
        }

        return response()->json([
            'projects' => $projectsToReturn
        ]);
    }
}
