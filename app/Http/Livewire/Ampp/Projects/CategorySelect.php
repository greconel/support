<?php

namespace App\Http\Livewire\Ampp\Projects;

use App\Enums\ProjectCategory;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CategorySelect extends Component
{
    public Project $project;
    public array $options = [];
    public ?string $selectedCategory = null;

    public function mount($projectId)
    {
        $this->project = Project::withTrashed()->find($projectId);
        $this->options = ProjectCategory::ForSelect();
        $this->selectedCategory = $this->project->category->value;
    }

    public function updatedSelectedCategory($value)
    {
        $this->project->update(['category' => $value]);
    }

    public function render(): View
    {
        return view('livewire.ampp.projects.category-select');
    }
}
