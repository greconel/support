<?php

namespace App\View\Components\Ampp\ProjectReports;

use App\Models\Project;
use App\Models\User;
use App\Traits\TimeConversionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class UserTable extends Component
{
    use TimeConversionTrait;

    public function __construct(
        public Collection $users,
        public Carbon $from,
        public Carbon $till
    ) {
    }

    public function calculateHours(User $user): string
    {
        $seconds = $user
            ->timeRegistrations()
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function calculateBillableHours(User $user): string
    {
        $seconds = $user
            ->timeRegistrations()
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->where('is_billable', '=', true)
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function getProjectsWithHours(User $user): array
    {
        $userProjectsWorkedIn = Project::contributedBetween(
            $user,
            $this->from->copy()->startOfDay(),
            $this->till->copy()->endOfDay()
        )->get();

        $data['projects'] = $userProjectsWorkedIn
            ->pluck('name_with_client')
            ->toArray()
        ;

        $data['durations'] = [];
        $data['colors'] = [];

        foreach ($userProjectsWorkedIn as $project){
            $seconds = $project
                ->timeRegistrations()
                ->whereBetween('start', [
                    $this->from->copy()->startOfDay(),
                    $this->till->copy()->endOfDay()
                ])
                ->where('user_id', '=', $user->id)
                ->sum('total_time_in_seconds');

            $data['durations'][] = ['seconds' => $seconds, 'hours' => $this->secondsToHoursAndMinutes($seconds)];
            $data['colors'][] = $project->color;
        }

        $otherTimeRegistrations = $user
            ->timeRegistrations()
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->where('project_id', '=', null)
            ->get();

        if ($otherTimeRegistrations->count() > 0){
            $data['projects'][] = __('Other');
            $seconds = $otherTimeRegistrations->sum('total_time_in_seconds');

            $data['durations'][] = ['seconds' => $seconds, 'hours' => $this->secondsToHoursAndMinutes($seconds)];
            $data['colors'][] = '#' . substr(md5(rand()), 0, 6);
        }

        return $data;
    }

    public function render(): View
    {
        return view('components.ampp.project-reports.user-table');
    }
}
