<?php

namespace App\Http\Livewire\Ampp\Projects;

use App\Models\Project;
use App\Traits\MediaTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Files extends Component
{
    use WithFileUploads;
    use MediaTrait;

    public Project $project;
    public $files = [];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function save()
    {
        $this->validate([
            'files' => ['required'],
            'files.*' => ['file', 'max:15000']
        ]);

        foreach ($this->files as $file){
            $this->project
                ->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection('files', 'private');
        }

        $this->reset('files');

        $this->project->refresh();
    }

    public function download($id): BinaryFileResponse
    {
        return $this->downloadMedia($this->project->getMedia('files')->firstWhere('id', $id));
    }

    public function delete($id)
    {
        $this->project->getMedia('files')->find($id)?->delete();

        $this->project->refresh();
    }

    public function render(): View
    {
        return view('livewire.ampp.projects.files');
    }
}
