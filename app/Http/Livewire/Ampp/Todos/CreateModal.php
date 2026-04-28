<?php

namespace App\Http\Livewire\Ampp\Todos;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class CreateModal extends Component
{
    public Model $model;
    public ?string $title = null;
    public ?string $description = null;
    public ?string $endDate = null;

    public function mount(Model $model)
    {
        $this->model = $model;
    }

    public function store()
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'endDate' => ['nullable', 'date_format:Y-m-d H:i'],
        ]);

        $this->model->todos()->create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'end_date' => $this->endDate,
        ]);

        $this->dispatch('toggle')->self();
        $this->dispatch('refreshQuill')->self();
        $this->dispatch('refreshTodos');

        $this->reset('title', 'description', 'endDate');
    }

    public function render(): View
    {
        return view('livewire.ampp.todos.create-modal');
    }
}
