<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class ShowInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return view('ampp.invoices.show', [
            'invoice' => $invoice
        ]);
    }
}
