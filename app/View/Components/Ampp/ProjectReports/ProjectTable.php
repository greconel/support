<?php

namespace App\View\Components\Ampp\ProjectReports;

use App\Models\Project;
use App\Traits\TimeConversionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ProjectTable extends Component
{
    use TimeConversionTrait;

    public function __construct(
        public Collection $projects,
        public Carbon $from,
        public Carbon $till
    ) {}

    public function calculateHours(Project $project): string
    {
        $seconds = $project
            ->timeRegistrations()
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function calculateBillableHours(Project $project): string
    {
        $seconds = $project
            ->timeRegistrations()
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->where('is_billable', '=', true)
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function render(): View
    {
        return view('components.ampp.project-reports.project-table');
    }
}
