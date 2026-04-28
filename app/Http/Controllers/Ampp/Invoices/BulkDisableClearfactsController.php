<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Invoices\BulkDisableClearfactsRequest;
use App\Models\Invoice;

class BulkDisableClearfactsController extends Controller
{
    public function __invoke(BulkDisableClearfactsRequest $request)
    {
        foreach ($request->input('invoices') as $invoiceId) {
            $invoice = Invoice::find($invoiceId);

            $this->authorize('update', $invoice);

            $invoice->update(['is_disabled_for_clearfacts' => true]);
        }

        session()->flash('success', __('The selected invoices are now disabled for Clearfacts bulk'));

        return redirect()->action(IndexClearfactsBulkInvoiceController::class);
    }
}
