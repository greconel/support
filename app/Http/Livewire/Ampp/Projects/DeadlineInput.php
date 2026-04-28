<?php

namespace App\Http\Livewire\Ampp\Projects;

use App\Models\Project;
use Exception;
use Livewire\Component;
use Illuminate\Support\Carbon;

class DeadlineInput extends Component
{
    public Project $project;
    public string $deadline;
    public string $today;

    public string $inputBackground = 'white';

    public function mount($projectId)
    {
        $this->project = Project::withTrashed()->find($projectId);
        $this->deadline = $this->project->deadline ? Carbon::parse($this->project->deadline)->format('Y-m-d') : '';
        $this->today = Carbon::now()->toDateString();
    }

    public function updatedDeadline($value)
    {
        if($value == '') $value = null;

        try {
            if($this->project->update(['deadline' => $value]) === true) {
                $this->inputBackground = '#cbffcc';
            }
        } catch (Exception $e) {
            $this->inputBackground = '#ffcbcb';
        }
    }
    public function render()
    {
        return view('livewire.ampp.projects.deadline-input');
    }
}
