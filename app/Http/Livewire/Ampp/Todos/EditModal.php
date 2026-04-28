<?php

namespace App\Http\Livewire\Ampp\Todos;

use App\Models\Todo;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class EditModal extends Component
{
    use AuthorizesRequests;

    public Model $model;
    public Todo $todo;
    public string $endDate = '';

    protected $rules = [
        'model.todos.*.finished' => ['nullable', 'boolean'],
        'todo.title' => ['string', 'required', 'max:255'],
        'todo.description' => ['string', 'nullable'],
        'endDate' => ['date_format:Y-m-d H:i', 'required'],
    ];

    public function mount()
    {
        $this->todo = new Todo();
    }

    #[On('editTodo')]
    public function editTodo(string $model, int $modelId, int $todoId)
    {
        try {
            $this->model = $model::find($modelId);

            $this->authorize('update', $this->model);

            $this->todo = $this->model->todos->find($todoId);
            $this->endDate = $this->todo->end_date->format('Y-m-d H:i');

            $this->dispatch('open')->self();
            $this->dispatch('refreshQuill')->self();
        } catch (\Exception){}
    }

    public function update()
    {
        $this->validate();

        $this->todo->end_date = Carbon::parse($this->endDate);
        $this->todo->save();

        $this->dispatch('close')->self();
        $this->dispatch('refreshTodos');
    }

    public function delete()
    {
        $this->todo->delete();

        $this->dispatch('close')->self();
        $this->dispatch('refreshTodos');
    }

    public function render(): View
    {
        return view('livewire.ampp.todos.edit-modal');
    }
}
