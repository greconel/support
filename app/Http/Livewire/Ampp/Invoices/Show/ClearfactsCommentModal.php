<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ClearfactsCommentModal extends Component
{
    public Invoice $invoice;

    protected $rules = [
        'invoice.clearfacts_comment' => ['nullable', 'string']
    ];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function updateClearfactsComment()
    {
        $this->validate();
        $this->invoice->save();

        $this->dispatch('close')->self();
        $this->dispatch('refreshQuill')->self();
        $this->dispatch('refreshInvoice')->to('ampp.invoices.show.clearfacts-comment');
    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.clearfacts-comment-modal');
    }
}
