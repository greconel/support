<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class PdfComment extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    #[On('refreshInvoice')]
    public function refreshInvoice(): void
    {
        $this->invoice->refresh();
    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.pdf-comment');
    }
}
