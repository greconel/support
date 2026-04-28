<?php

namespace App\Http\Livewire\Ampp\Projects;

use App\Enums\ProjectCategory;
use App\Models\Project;
use App\Models\User;
use Livewire\Component;

class AssigneeSelect extends Component
{
    public Project $project;
    public array $options = [];
    public ?string $selectedAssignee = null;

    public function mount($projectId)
    {
        $this->project = Project::withTrashed()->find($projectId);
        $this->options = User::pluck('name', 'id')->prepend('Team', 'team')->prepend('', 0)->all();
        $this->selectedAssignee = $this->project->assignee;
    }

    public function updatedSelectedAssignee($value)
    {
        if($value == 0) {
            $value = null;
        }
        $this->project->update(['assignee' => $value]);
    }
    public function render()
    {
        return view('livewire.ampp.projects.assignee-select');
    }
}
