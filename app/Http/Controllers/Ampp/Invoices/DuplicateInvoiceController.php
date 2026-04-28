<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Carbon\Carbon;

class DuplicateInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $this->authorize('create', Invoice::class);

        $expirationPeriod = Carbon::parse($invoice->expiration_date)->endOfDay()->diffInDays(Carbon::parse($invoice->custom_created_at)->startOfDay(), absolute: true);

        $duplicateInvoice = $invoice->replicate();
        $duplicateInvoice->parent_invoice_id = $invoice->id;
        $duplicateInvoice->notes = null;
        $duplicateInvoice->clearfacts_comment = null;
        $duplicateInvoice->created_at = Carbon::now();
        $duplicateInvoice->custom_created_at = Carbon::now();
        $duplicateInvoice->expiration_date = Carbon::now()->addDays($expirationPeriod);
        $duplicateInvoice->sent_to_clearfacts_at = null;
        $duplicateInvoice->clearfacts_id = null;
        $duplicateInvoice->sent_to_recommand_at = null;
        $duplicateInvoice->recommand_document_id = null;
        $duplicateInvoice->paid_at = null;
        $duplicateInvoice->status = 0;
        $duplicateInvoice->save();

        $billingLines = $invoice->billingLines;

        foreach($billingLines as $billingLine) {
            $duplicateBillingLine = $billingLine->replicate();
            $duplicateBillingLine->created_at = Carbon::now();
            $duplicateBillingLine->model_id = $duplicateInvoice->id;
            $duplicateBillingLine->save();
        }

        return redirect()->action(ShowInvoiceController::class, ['invoice' => $duplicateInvoice->id]);
    }
}
