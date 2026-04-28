<?php

namespace App\Http\Livewire\Ampp\Quotations\Show;

use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class NoteModal extends Component
{
    public Quotation $quotation;

    protected $rules = [
        'quotation.notes' => ['nullable', 'string']
    ];

    public function mount(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function updateNotes()
    {
        $this->validate();
        $this->quotation->save();

        $this->dispatch('close')->self();
        $this->dispatch('refreshQuill')->self();
        $this->dispatch('refreshQuotation')->to('ampp.quotations.show.notes');

    }

    public function render(): View
    {
        return view('livewire.ampp.quotations.show.note-modal');
    }
}
