<?php

namespace App\Http\Livewire\Ampp\Todos;

use App\Models\Todo;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public Model $model;

    protected $rules = [
        'model.todos.*.finished' => ['nullable', 'boolean'],
    ];

    public function mount(Model $model)
    {
        $this->model = $model;
    }

    public function updatedModel()
    {
        foreach ($this->model->todos as $todo){
            $todo->save();
        }
    }

    #[On('refreshTodos')]
    public function refreshTodos()
    {
        $this->model->refresh();
    }

    public function edit($id)
    {
        $this->dispatch('editTodo', model: $this->model::class, modelId: $this->model->id, todoId: $id);
    }

    public function render(): View
    {
        return view('livewire.ampp.todos.index', [
            'todos' => $this->model->todos->sortBy('end_date')->sortBy('finished')
        ]);
    }
}
