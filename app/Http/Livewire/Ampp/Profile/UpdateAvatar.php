<?php

namespace App\Http\Livewire\Ampp\Profile;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateAvatar extends Component
{
    use WithFileUploads;

    public $photo;

    public function render(): View
    {
        return view('livewire.ampp.profile.update-avatar');
    }
}
