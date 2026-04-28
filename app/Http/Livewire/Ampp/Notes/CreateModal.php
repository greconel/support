<?php

namespace App\Http\Livewire\Ampp\Notes;

use App\Models\Note;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class CreateModal extends Component
{
    public Model $model;
    public Note $note;

    protected $rules = [
        'note.user_id' => ['required', 'int'],
        'note.title' => ['required', 'string', 'max:255'],
        'note.description' => ['nullable', 'string'],
    ];

    public function mount(Model $model)
    {
        $this->model = $model;
        $this->note = new Note(['user_id' => auth()->id()]);
    }

    public function create()
    {
        $this->validate();

        $this->model->notes()->save($this->note);
        $this->note = new Note(['user_id' => auth()->id()]);

        $this->dispatch('close')->self();
        $this->dispatch('refreshQuill')->self();
        $this->dispatch('refreshNotes');
    }

    public function render(): View
    {
        return view('livewire.ampp.notes.create-modal');
    }
}
