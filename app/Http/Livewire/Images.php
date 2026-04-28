<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;

class Images extends Component
{
    use WithFileUploads;

    public $image;
    public $images = [];
    public ?Model $model;

    public function mount(Model $model = null)
    {
        $this->model = $model;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image'
        ]);
    }

    public function add()
    {
        $this->validate([
            'image' => ['image']
        ]);

        array_push($this->images, $this->image);

        $this->image = null;
    }

    public function delete($index)
    {
        unset($this->images[$index]);
    }

    public function deleteExisting($id)
    {
        $this->model->deleteMedia($id);
        $this->model->refresh();
    }

    public function render(): View
    {
        return view('livewire.images');
    }
}
