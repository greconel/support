<?php

namespace App\Http\Controllers\Ampp\ProjectReports;

use App\DataTables\Ampp\DetailedTimeReportDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\ProjectReports\ShowDetailedTimeReportRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeRegistration;
use Illuminate\Support\Carbon;

class ShowDetailedTimeReportController extends Controller
{
    public function __invoke(ShowDetailedTimeReportRequest $request, DetailedTimeReportDataTable $dataTable)
    {
        $this->authorize('reports', Project::class);

        $timeRegistrations = TimeRegistration::query()
            ->whereBetween('start', [
                Carbon::parse($request->input('from'))->startOfDay(),
                Carbon::parse($request->input('till'))->endOfDay()
            ])
        ;

        if ($request->input('type') == 'project'){
            $timeRegistrations->where('project_id', $request->input('id'));
            $name = __('Project: ') . Project::find($request->input('id'))->name_with_client;
        }

        if ($request->input('type') == 'client'){
            $timeRegistrations->whereRelation('project', 'client_id', '=', $request->input('id'));
            $name = __('Client: ') . Client::find($request->input('id'))->full_name_with_company;
        }

        $totalHours = floor($timeRegistrations->sum('total_time_in_seconds') / 3600);
        $minutes = floor(($timeRegistrations->sum('total_time_in_seconds') % 3600) / 60);

        $totalHoursBillable = floor($timeRegistrations->where('is_billable', '=', true)->sum('total_time_in_seconds') / 3600);
        $minutesBillable = floor(($timeRegistrations->where('is_billable', '=', true)->sum('total_time_in_seconds') % 3600) / 60);

        $hoursByProjectAndActivity = $timeRegistrations->where('is_billable', '=', true)->get()
            ->groupBy(['project_id', 'project_activity_id']);

        $hoursByProjectAndActivityArray = [];
        foreach ($hoursByProjectAndActivity as $projectId => $project) {
            $map = $project->map(function ($activities) {
                return round($activities->sum('total_time_in_seconds') / 3600, 2);
            });

            $hoursByProjectAndActivityArray[$projectId] = $map->toArray();
        }

        return $dataTable
            ->with($request->validated())
            ->render('ampp.projectReports.detailedTimeReport', [
                'from' => $request->input('from'),
                'till' => $request->input('till'),
                'name' => $name ?? null,
                'totalHours' => $totalHours,
                'minutes' => $minutes,
                'totalHoursBillable' => $totalHoursBillable,
                'minutesBillable' => $minutesBillable,
                'hoursByProjectAndActivity' => $hoursByProjectAndActivityArray,
            ])
        ;
    }
}
