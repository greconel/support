<?php

namespace App\Http\Livewire\Ampp\Projects;

use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;

class Description extends Component
{
    public Project $project;
    public bool $editMode = false;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    #[On('enableEdit')]
    public function enableEdit()
    {
        $this->editMode = !$this->editMode;
        $this->dispatch('initTinyMce');
    }

    public function edit($description)
    {
        $this->project->update(['description' => $description]);
        $this->reset('editMode');
        $this->dispatch('destroyTinyMce');

    }

    public function render()
    {
        return view('livewire.ampp.projects.description');
    }
}
