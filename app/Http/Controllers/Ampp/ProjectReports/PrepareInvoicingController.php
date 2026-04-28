<?php

namespace App\Http\Controllers\Ampp\ProjectReports;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeRegistration;
use App\Traits\TimeConversionTrait;
use Illuminate\Http\Request;

class PrepareInvoicingController extends Controller
{
    use TimeConversionTrait;

    public function __invoke(Request $request)
    {
        $this->authorize('reports', Project::class);

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:time_registrations,id'],
        ]);

        $timeRegistrations = TimeRegistration::with(['user', 'project', 'projectActivity', 'projectClient'])
            ->whereIn('id', $request->input('ids'))
            ->where('is_billed', false)
            ->orderBy('start')
            ->get();

        if ($timeRegistrations->isEmpty()) {
            return back()->with('error', __('No unprocessed time registrations found.'));
        }

        // Determine client
        $client = $timeRegistrations->first()->projectClient;

        // Group by activity
        $grouped = $timeRegistrations->groupBy(function ($tr) {
            return $tr->project_activity_id ?? 0;
        });

        $sections = [];
        foreach ($grouped as $activityId => $items) {
            $activity = $activityId ? $items->first()->projectActivity : null;
            $totalSeconds = $items->sum('total_time_in_seconds');
            $billableSeconds = $items->where('is_billable', true)->sum('total_time_in_seconds');

            $sections[] = [
                'activity_id' => $activityId,
                'activity_name' => $activity?->name ?? __('No activity'),
                'time_registrations' => $items,
                'total_seconds' => $totalSeconds,
                'billable_seconds' => $billableSeconds,
            ];
        }

        return view('ampp.projectReports.prepareInvoicing', [
            'sections' => $sections,
            'client' => $client,
            'ids' => $request->input('ids'),
        ]);
    }
}
