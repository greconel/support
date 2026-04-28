<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class ToggleClearfactsController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $invoice->update(['is_disabled_for_clearfacts' => ! $invoice->is_disabled_for_clearfacts]);

        session()->flash(
            'success',
            __('Clearfacts bulk is now :status for this invoice', ['status' => $invoice->is_disabled_for_clearfacts ? __('disabled') : __('enabled')])
        );

        return redirect()->action(ShowInvoiceController::class, $invoice);
    }
}
