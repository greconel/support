<?php

namespace App\Http\Controllers\Api\V1\Invoices;

use App\Enums\InvoiceStatus;
use App\Http\Requests\Api\V1\Invoices\StoreInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\BillingLines;
use App\Models\Invoice;

class StoreInvoiceController
{
    /**
     * Create a new invoice
     *
     * @group Invoices
     * @header Accept application/json
     *
     * @bodyParam type string required The type of invoice, this can be debit or credit
     * @bodyParam status int required The status of the invoice, This can be 0 (draft), 1 (sent), 2 (reminder), 3 (paid)
     *
     */
    public function __invoke(StoreInvoiceRequest $request)
    {
        $invoice = Invoice::create([
            'type' => $request->input('type'),
            'client_id' => $request->input('client_id'),
            'status' => $request->input('status'),
            'notes' => $request->input('pdf_comment'),
            'pdf_comment' => $request->input('pdf_comment'),
            'expiration_date' => $request->input('expiration_date'),
            'custom_created_at' => $request->input('custom_created_at'),
        ]);

        foreach ($request->input('billing_lines') as $billingLine) {
            $invoice
                ->billingLines()
                ->create([
                    'type' => $billingLine['type'],
                    'order' => $billingLine['order'],
                    'text' => $billingLine['text'],
                    'price' => $billingLine['price'],
                    'subtotal' => $billingLine['subtotal'],
                    'amount' => $billingLine['amount'],
                    'vat' => $billingLine['vat'],
                    'discount' => $billingLine['discount'],
                    'description' => $billingLine['description'],
                ]);
        }

        $invoice->refresh();

        $invoice->fill([
            'amount' => $invoice->billingLines->sum('subtotal'),
            'amount_with_vat' => $invoice
                ->billingLines
                ->where('type', '=', 'text')
                ->whereNotNull('subtotal')
                ->whereNotNull('vat')
                ->sum(fn(BillingLines $line) => $line->subtotal * (($line->vat / 100) + 1))
        ]);

        if ($invoice->status === InvoiceStatus::Paid){
            $invoice->paid_at = now();
        }

        $invoice->save();

        return new InvoiceResource($invoice->refresh());
    }
}
