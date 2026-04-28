<?php

namespace App\Http\Livewire\Ampp\Notes;

use App\Models\Note;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public Model $model;

    public function mount(Model $model)
    {
        $this->model = $model;
    }

    #[On('refreshNotes')]
    public function refreshNotes(): void
    {
        // Component will re-render automatically
    }

    public function edit($id)
    {
        $this->dispatch('editNote', model: $this->model::class, modelId: $this->model->id, id: $id);
    }

    public function render(): View
    {
        return view('livewire.ampp.notes.index', [
            'notes' => $this->model->notes
        ]);
    }
}
