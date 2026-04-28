<?php

namespace App\View\Components\Ampp\Invoices\Show;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InvoiceOverview extends Component
{
    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function render(): View
    {
        return view('components.ampp.invoices.show.invoice-overview');
    }
}
