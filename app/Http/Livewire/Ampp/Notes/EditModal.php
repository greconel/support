<?php

namespace App\Http\Livewire\Ampp\Notes;

use App\Models\Note;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\On;
use Livewire\Component;

class EditModal extends Component
{
    use AuthorizesRequests;

    public Model $model;
    public Note $note;

    protected $rules = [
        'note.title' => ['required', 'string', 'max:255'],
        'note.description' => ['nullable', 'string'],
    ];

    public function mount()
    {
        $this->note = new Note();
    }

    #[On('editNote')]
    public function editNote(string $model, int $modelId, int $noteId)
    {
        try {
            $this->model = $model::find($modelId);

            $this->authorize('update', $this->model);

            $this->note = $this->model->notes->find($noteId);

            $this->dispatch('toggle')->self();
            $this->dispatch('refreshQuill')->self();
        } catch (\Exception){}
    }

    public function update()
    {
        $this->validate();

        $this->note->save();

        $this->dispatch('toggle')->self();
        $this->dispatch('refreshQuill')->self();
        $this->dispatch('refreshNotes');
    }

    public function delete($id)
    {
        $this->note = $this->model->notes->find($id);
        $this->note->delete();

        $this->dispatch('toggle')->self();
        $this->dispatch('refreshNotes');
    }

    public function render(): View
    {
        return view('livewire.ampp.notes.edit-modal');
    }
}
