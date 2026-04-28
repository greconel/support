<?php

namespace App\Http\Livewire\Ampp\ProjectReports;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeRegistration;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Overview extends Component
{
    /** @var Carbon|null $from */
    public $from;

    /** @var Carbon|null $till */
    public $till;

    /** @var Collection<Client> $clients */
    public Collection $clients;

    /** @var Collection<Project> $projects */
    public Collection $projects;

    /** @var Collection<TimeRegistration> $timeRegistrations */
    public Collection $otherTimeRegistrations;

    /** @var Collection<User> $users */
    public Collection $users;

    public function mount()
    {
        try {
            $this->from = request()->input('from') ? Carbon::parse(request()->input('from')) : now()->firstOfMonth();
            $this->till = request()->input('till') ? Carbon::parse(request()->input('till')) : now()->lastOfMonth();
        } catch (\Exception) {
            $this->from = now()->firstOfMonth();
            $this->till = now()->lastOfMonth();
        }

        $this->report();
    }

    #[On('report')]
    public function report()
    {
        $clientIds = TimeRegistration::with('project.client:id')
            ->whereHas('project.client')
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->get()
            ->pluck('project.client_id')
            ->toArray();

        $this->clients = Client::whereIn('id', $clientIds)->get();

        $projectIds = TimeRegistration::with('project')
            ->whereHas('project')
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->get()
            ->pluck('project_id')
            ->toArray();

        $this->projects = Project::whereIn('id', $projectIds)->get();

        $this->otherTimeRegistrations = TimeRegistration::general()
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->get();

        $userIds = TimeRegistration::whereBetween('start', [
            $this->from->copy()->startOfDay(),
            $this->till->copy()->endOfDay()
        ])
            ->get()
            ->pluck('user_id')
            ->toArray();

        $this->users = User::whereIn('id', $userIds)->get();
    }

    public function getTotalHoursProperty()
    {
        $seconds = TimeRegistration::whereBetween('start', [
            $this->from->copy()->startOfDay(),
            $this->till->copy()->endOfDay()
        ])->sum('total_time_in_seconds');

        return floor($seconds / 3600);
    }

    public function getMinutesProperty()
    {
        $seconds = TimeRegistration::whereBetween('start', [
            $this->from->copy()->startOfDay(),
            $this->till->copy()->endOfDay()
        ])->sum('total_time_in_seconds');

        return floor(($seconds % 3600) / 60);
    }

    public function getTotalHoursBillableProperty()
    {
        $seconds = TimeRegistration::whereBetween('start', [
            $this->from->copy()->startOfDay(),
            $this->till->copy()->endOfDay()
        ])
            ->where('is_billable', '=', true)
            ->sum('total_time_in_seconds');

        return floor($seconds / 3600);
    }

    public function getMinutesBillableProperty()
    {
        $seconds = TimeRegistration::whereBetween('start', [
            $this->from->copy()->startOfDay(),
            $this->till->copy()->endOfDay()
        ])
            ->where('is_billable', '=', true)
            ->sum('total_time_in_seconds');

        return floor(($seconds % 3600) / 60);
    }

    public function getTopMostClientProperty()
    {
        $seconds = 0;
        $topClient = null;

        foreach (Client::all() as $client){
            $clientSeconds = $client
                ->timeRegistrations()
                ->whereBetween('start', [
                    $this->from->copy()->startOfDay(),
                    $this->till->copy()->endOfDay()
                ])
                ->sum('total_time_in_seconds')
            ;

            if ($clientSeconds > $seconds){
                $seconds = $clientSeconds;
                $topClient = $client;
            }
        }

       return $topClient;
    }

    public function getBusiestProjectProperty()
    {
        $seconds = 0;
        $busiestProject = null;

        foreach (Project::all() as $project){
            $projectSeconds = $project
                ->timeRegistrations()
                ->whereBetween('start', [
                    $this->from->copy()->startOfDay(),
                    $this->till->copy()->endOfDay()
                ])
                ->sum('total_time_in_seconds')
            ;

            if ($projectSeconds > $seconds){
                $seconds = $projectSeconds;
                $busiestProject = $project;
            }
        }

        return $busiestProject;
    }

    #[On('shareDates')]
    public function shareDates(?string $from, ?string $till)
    {
        $this->from = $from ? Carbon::parse($from) : now()->subMillennia(2);
        $this->till = $till ? Carbon::parse($till) : now()->addMillennia(2);

        $this->report();
    }

    public function render(): View
    {
        return view('livewire.ampp.project-reports.overview');
    }
}
