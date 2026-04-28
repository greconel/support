<?php

namespace App\Http\Livewire\Admin\Connections;

use Livewire\Component;
use Livewire\WithFileUploads;

class CreateConnection extends Component
{
    use WithFileUploads;

    public $photo;
    public function render()
    {
        return view('livewire.admin.connections.create-connection');
    }
}