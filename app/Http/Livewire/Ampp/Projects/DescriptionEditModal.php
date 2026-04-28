<?php

namespace App\Http\Livewire\Ampp\Projects;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class DescriptionEditModal extends Component
{
    use LivewireAlert;

    public Project $project;

    protected $rules = [
        'project.description' => ['nullable', 'string'],
    ];

    public function mount()
    {
        $this->project = new Project();
    }

    #[On('editDescription')]
    public function editDescription(int $projectId)
    {
        $this->project = Project::withTrashed()->find($projectId);

        $this->resetErrorBag();
        $this->dispatch('show')->self();
        $this->dispatch('refreshQuill')->self();
    }

    public function update()
    {
        $this->project->save();

        $this->dispatch(
            "updated-description-for-{$this->project->id}",
            value: $this->project->fresh()->description
        );

        $this->dispatch('hide')->self();

        $this->alert('success', __('Project updated'), ['position' => 'top']);
    }

    public function render(): View
    {
        return view('livewire.ampp.projects.description-edit-modal');
    }
}
