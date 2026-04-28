<?php

namespace App\Http\Livewire\Ampp\TimeRegistrations;

use App\Models\Project;
use App\Models\TimeRegistration;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class MonthView extends DayView
{
    public ?string $projectId = null;
    public array $projects = [];

    public function mount()
    {
        $this->userId = $this->userId ?? auth()->id();

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

        $this->resetProjectsSelect();
    }

    public function queryString(): array
    {
        return [
            'userId' => ['as' => 'user', 'except' => strval(auth()->id())],
            'dateStr' => ['as' => 'date'],
            'projectId' => ['as' => 'project', 'except' => null]
        ];
    }

    public function updatedUserId()
    {
        $this->dispatch('updateCalendarUser', userId: $this->userId);
    }

    public function updatedProjectId()
    {
        $this->dispatch('updateCalendarProject', projectId: $this->projectId);
    }

    public function nextMonth()
    {
        $this->date->addMonth();
        $this->dispatch('updateCalendarDate', date: $this->date->format('Y-m-d'));
    }

    public function previousMonth()
    {
        $this->date->subMonth();
        $this->dispatch('updateCalendarDate', date: $this->date->format('Y-m-d'));
    }

    public function today()
    {
        parent::today();
        $this->dispatch('updateCalendarDate', date: $this->date->format('Y-m-d'));
    }

    private function resetProjectsSelect()
    {
        $this->projects = User::find($this->userId)
            ->projects()
            ->orderBy('name')
            ->get()
            ->map(function (Project $project){
                return [
                    'name' => $project->name,
                    'value' => $project->id
                ];
            })
            ->prepend(['name' => __('All projects'), 'value' => ''])
            ->toArray()
        ;
    }

    public function render(): View
    {
        if (User::find($this->userId)->hasRole('super admin')){
            abort(403);
        }
        if (auth()->user()->cannot('viewOtherUsers', TimeRegistration::class) && $this->userId != auth()->id()){
            abort(403);
        }

        return view('livewire.ampp.time-registrations.month-view');
    }
}
