<?php

namespace App\Http\Livewire\Ampp\TimeRegistrations;

use App\Models\Project;
use App\Models\TimeRegistration;
use App\Models\User;
use App\Traits\TimeConversionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class DayView extends Component
{
    use TimeConversionTrait;
    use AuthorizesRequests;

    public ?string $userId = null;
    public array $users = [];
    public Carbon $date;
    public string $dateStr = '';

    public function mount()
    {
        if($this->userId && User::find($this->userId)->hasRole('super admin') && !auth()->user()->hasRole('super admin')) {
            abort(403);
        } else {
            $this->userId = $this->userId ?? auth()->id();
        }

        try {
            $this->date = $this->dateStr ? Carbon::parse($this->dateStr) : now();
        } catch (\Exception){
            $this->date = now();
        }

        if (auth()->user()->can('viewOtherUsers', TimeRegistration::class)){
            $this->users = User::whereRelation('roles', 'name', '!=', 'super admin')
                ->pluck('name', 'id')
                ->toArray();
        }
        if (auth()->user()->hasRole('super admin')){
            $this->users = User::all()
                ->pluck('name', 'id')
                ->toArray();
        }
    }

    public function queryString(): array
    {
        return [
            'dateStr' => ['as' => 'date'],
            'userId' => ['as' => 'user', 'except' => strval(auth()->id())]
        ];
    }

    public function dehydrate()
    {
        $this->dateStr = $this->date->format('Y-m-d');
    }

    #[On('refreshTimeRegistrations')]
    public function refreshTimeRegistrations()
    {
        // This method triggers a component refresh when the event is dispatched
    }

    public function setDate(string $dateFromString)
    {
        $this->date = Carbon::parse($dateFromString);
    }

    public function previousWeek()
    {
        $this->date->subWeek();
    }

    public function nextWeek()
    {
        $this->date->addWeek();
    }

    public function today()
    {
        $this->date = now();
    }

    public function getDaysOfWeekProperty(): Collection
    {
        $weekdays = collect();

        $date = $this->date->copy()->startOfWeek(Carbon::MONDAY);

        $weekdays->push($date->copy()->startOfWeek());
        $weekdays->push($date->copy()->weekday(2));
        $weekdays->push($date->copy()->weekday(3));
        $weekdays->push($date->copy()->weekday(4));
        $weekdays->push($date->copy()->weekday(5));
        $weekdays->push($date->copy()->weekday(6));
        $weekdays->push($date->copy()->endOfWeek());

        return $weekdays;
    }

    public function calculateTimeForDay(Carbon $day): string
    {
        $seconds = TimeRegistration::where('user_id', $this->userId)
            ->whereDate('start', '=', $day)
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function calculateTimeForWeek(): string
    {
        $seconds = TimeRegistration::where('user_id', $this->userId)
            ->whereBetween('start', [
                $this->date->copy()->startOfWeek()->format('Y-m-d H:i'),
                $this->date->copy()->endOfWeek()->format('Y-m-d H:i')
            ])
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function getLatestProjectsProperty()
    {
        $projectIds = TimeRegistration::where('user_id', '=', $this->userId)
            ->whereNotNull('project_id')
            ->latest('start')
            ->get()
            ->unique('project_id')
            ->take(12)
            ->pluck('project_id')
            ->toArray()
        ;

        return Project::whereIn('id', $projectIds)
            ->get()
            ->map(function (Project $project){
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'client_name' => $project->client?->full_name,
                    'color' => $project->color
                ];
            })
            ->toArray();
    }

    public function startNewTimer(Project $project = null)
    {
        if ($project->id){
            $this->authorize('view', $project);
        }

        auth()->user()->timeRegistrations()->create(['start' => now(), 'project_id' => $project->id]);

        $this->userId = auth()->id();
    }

    public function create(Project $project = null)
    {
        $this->dispatch(
            'createTimeRegistration',
            date: $this->date->format('Y-m-d'),
            userId: $this->userId,
            projectId: strval($project?->id)
        );
    }

    public function render(): View
    {
        if (auth()->user()->cannot('viewOtherUsers', TimeRegistration::class) && $this->userId != auth()->id()){
            abort(403);
        }

        return view('livewire.ampp.time-registrations.day-view', [
            'timeRegistrations' => TimeRegistration::where('user_id', $this->userId)
                ->whereDate('start', '=', $this->date)
                ->orderBy('start')
                ->get()
        ]);
    }
}
