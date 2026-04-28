<?php

namespace App\Http\Livewire\Ampp\ProjectReports;

use App\Models\Project;
use App\Models\TimeRegistration;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProjectSelect extends Component
{
    public TimeRegistration $timeRegistration;
    public array $projects = [];
    public string $projectId = '';

    public function mount(TimeRegistration $timeRegistration)
    {
        $this->timeRegistration = $timeRegistration;

        $this->projects = Project::where('is_general', '=', false)
            ->get()
            ->pluck('name_with_client', 'id')
            ->prepend(__('Change project'), null)
            ->toArray();

        $this->projectId = $timeRegistration->project_id ?? '';
    }

    public function updatedProjectId()
    {
        if ($this->projectId){
            $this->timeRegistration->update(['project_id' => $this->projectId]);
            $this->timeRegistration->refresh();
        }
    }

    public function undo()
    {
        $this->timeRegistration->update(['project_id' => null]);
        $this->timeRegistration->refresh();
        $this->reset('projectId');
    }

    public function render(): View
    {
        return view('livewire.ampp.project-reports.project-select');
    }
}
