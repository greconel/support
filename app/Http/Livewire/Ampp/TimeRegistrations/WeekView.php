<?php

namespace App\Http\Livewire\Ampp\TimeRegistrations;

use App\Models\Project;
use App\Models\TimeRegistration;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class WeekView extends DayView
{
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
    }

    public function getProjectIdsProperty(): array
    {
        return TimeRegistration::with('project')
            ->where('user_id', $this->userId)
            ->whereBetween('start', [
                $this->date->copy()->startOfWeek()->format('Y-m-d H:i'),
                $this->date->copy()->endOfWeek()->format('Y-m-d H:i')
            ])
            ->get()
            ->unique('project_id')
            ->pluck('project_id')
            ->sort()
            ->toArray()
        ;
    }

    public function calculateTimeForProjectOnDay(Carbon $day, Project $project = null): string
    {
        $seconds = TimeRegistration::where('user_id', $this->userId)
            ->whereDate('start', '=', $day)
            ->where('project_id', '=', $project?->id)
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function calculateTimeForProjectOnWeek(Project $project = null): string
    {
        $seconds = TimeRegistration::where('user_id', $this->userId)
            ->whereBetween('start', [
                $this->date->copy()->startOfWeek()->format('Y-m-d H:i'),
                $this->date->copy()->endOfWeek()->format('Y-m-d H:i')
            ])
            ->where('project_id', '=', $project?->id)
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function render(): View
    {
        if (User::find($this->userId)->hasRole('super admin')){
            abort(403);
        }
        if (auth()->user()->cannot('viewOtherUsers', TimeRegistration::class) && $this->userId != auth()->id()){
            abort(403);
        }

        return view('livewire.ampp.time-registrations.week-view');
    }
}
