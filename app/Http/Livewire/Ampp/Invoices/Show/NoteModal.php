<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class NoteModal extends Component
{
    public Invoice $invoice;

    protected $rules = [
        'invoice.notes' => ['nullable', 'string']
    ];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function updateNotes()
    {
        $this->validate();
        $this->invoice->save();

        $this->dispatch('close')->self();
        $this->dispatch('refreshQuill')->self();
        $this->dispatch('refreshInvoice')->to('ampp.invoices.show.notes');

    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.note-modal');
    }
}
