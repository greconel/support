<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class EditInvoiceLinesController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        return view('ampp.invoices.lines', [
            'invoice' => $invoice
        ]);
    }
}
