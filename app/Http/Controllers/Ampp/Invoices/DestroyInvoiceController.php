<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class DestroyInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);

        $invoice->delete();

        return redirect()
            ->action(IndexInvoiceController::class)
            ->with('success', __('Successfully deleted invoice'))
        ;
    }
}
