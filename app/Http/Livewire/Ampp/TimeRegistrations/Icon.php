<?php

namespace App\Http\Livewire\Ampp\TimeRegistrations;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

use function auth;
use function view;

class Icon extends Component
{
    public bool $busy = false;

    public function mount()
    {
        $this->refreshTimeRegistrations();
    }

    #[On('refreshTimeRegistrations')]
    public function refreshTimeRegistrations()
    {
        $this->busy = auth()->user()->timeRegistrations()->where('end', null)->exists();
    }

    public function render(): View
    {
        return view('livewire.ampp.time-registrations.icon');
    }
}
