<?php

namespace App\Http\Controllers\Ampp\Quotations;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Http\Controllers\Ampp\Invoices\ShowInvoiceController;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Quotation;

class ConvertQuotationToInvoiceController extends Controller
{
    public function __invoke(Quotation $quotation)
    {
        $this->authorize('create', Invoice::class);

        $invoice = Invoice::create([
            'client_id' => $quotation->client_id,
            'quotation_id' => $quotation->id,
            'type' => InvoiceType::Debit,
            'amount' => $quotation->amount,
            'amount_with_vat' => $quotation->amount_with_vat,
            'expiration_date' => now()->addDays(30),
            'custom_created_at' => now(),
        ]);

        $invoice->billingLines()->createMany($quotation->billingLines->toArray());

        return redirect()
            ->action(ShowInvoiceController::class, $invoice)
            ->with('success', __('Successfully converted quotation to invoice'))
        ;
    }
}
