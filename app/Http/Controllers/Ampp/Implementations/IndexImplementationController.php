<?php

namespace App\Http\Controllers\Ampp\Implementations;

use App\Enums\ImplementationStatus;
use App\Http\Controllers\Controller;
use App\Models\Implementation;
use App\Models\ImplementationSchedule;

class IndexImplementationController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Implementation::class);

        $implementations = Implementation::with(['latestSnapshot', 'beaconKey', 'schedules' => function ($q) {
                $q->where('is_active', true);
            }])
            ->withCount(['errors' => function ($q) {
                $q->where('created_at', '>=', now()->subDay());
            }])
            ->get();

        $stats = [
            'total' => $implementations->count(),
            'online' => $implementations->where('status', ImplementationStatus::Online)->count(),
            'degraded' => $implementations->where('status', ImplementationStatus::Degraded)->count(),
            'offline' => $implementations->where('status', ImplementationStatus::Offline)->count(),
        ];

        $allSchedules = ImplementationSchedule::with('implementation')
            ->where('is_active', true)
            ->get();

        $scheduleStats = [
            'total' => $allSchedules->count(),
            'on_time' => $allSchedules->filter(fn ($s) => $s->last_finished_at && $s->last_exit_code === 0 && !$s->is_overdue)->count(),
            'errored' => $allSchedules->filter(fn ($s) => $s->last_exit_code !== null && $s->last_exit_code !== 0)->count(),
            'overdue' => $allSchedules->filter->is_overdue->count(),
            'never_ran' => $allSchedules->filter(fn ($s) => $s->last_finished_at === null)->count(),
        ];

        return view('ampp.implementations.index', compact('implementations', 'stats', 'scheduleStats'));
    }
}
