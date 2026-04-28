<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class ConfirmInvoiceExistsInClearfactsController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $invoice->update([
            'sent_to_clearfacts_at' => now(),
        ]);

        session()->flash('success', __('Invoice marked as existing in Clearfacts.'));

        return redirect()->action(ShowInvoiceController::class, $invoice);
    }
}
