<?php

namespace App\Http\Livewire\Ampp\Projects;

use App\Models\Project;
use App\Traits\TimeConversionTrait;
use Carbon\CarbonInterval;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class TotalTime extends Component
{
    use TimeConversionTrait;

    public Project $project;
    public int $hoursRounded;
    public string $hoursPrecise;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->calculate();
    }

    #[On('refreshAllTimeRegistrationsExternal')]
    public function refreshAllTimeRegistrationsExternal()
    {
        $this->project->refresh();
        $this->calculate();
    }

    private function calculate()
    {
        $this->hoursRounded = round(CarbonInterval::seconds($this->project->timeRegistrations->sum('total_time_in_seconds'))->totalHours);
        $this->hoursPrecise = $this->secondsToHoursAndMinutes($this->project->timeRegistrations->sum('total_time_in_seconds'));
    }

    public function render(): View
    {
        return view('livewire.ampp.projects.total-time');
    }
}
