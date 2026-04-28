<?php

namespace App\Http\Livewire\Ampp\ClientContacts;

use App\Http\Controllers\Ampp\ClientContacts\CreateClientContactController;
use App\Models\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateModal extends Component
{
    public array $clients = [];
    public ?int $selectedClient = null;

    public function mount()
    {
        $this->clients = Client::all()->pluck('full_name', 'id')->toArray();
        $this->selectedClient = array_key_first($this->clients);
    }

    #[On('showModal')]
    public function showModal()
    {
        $this->dispatch('show')->self();
    }

    public function submit()
    {
        $this->validate([
            'selectedClient' => ['required', Rule::in(Client::pluck('id'))]
        ]);

        return redirect()->action(CreateClientContactController::class, $this->selectedClient);
    }

    public function render(): View
    {
        return view('livewire.ampp.client-contacts.create-modal');
    }
}
