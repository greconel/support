<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Invoice;

class ConvertInvoiceToCreditNoteController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $this->authorize('create', Invoice::class);

        if ($invoice->type == InvoiceType::Credit){
            session()->flash('error', __('You can not make a credit note from a credit note'));
            return redirect()->back();
        }

        $newInvoice = Invoice::create([
            'client_id' => $invoice->client_id,
            'quotation_id' => $invoice->quotation_id,
            'parent_invoice_id' => $invoice->id,
            'type' => InvoiceType::Credit,
            'amount' => $invoice->amount > 0 ? - abs($invoice->amount) : 0,
            'amount_with_vat' => $invoice->amount_with_vat > 0 ? - abs($invoice->amount_with_vat) : 0,
            'expiration_date' => now()->addDays(30),
            'custom_created_at' => now(),
            'invoice_category_id' => $invoice->invoice_category_id
        ]);

        foreach ($invoice->billingLines as $line){
            if ($line->type == 'text'){
                $line->price = $line->price > 0 ? - abs($line->price) : 0;
                $line->subtotal = $line->subtotal > 0 ? - abs($line->subtotal) : 0;
            }

            $newInvoice->billingLines()->create($line->toArray());
        }

        return redirect()
            ->action(ShowInvoiceController::class, $newInvoice)
            ->with('success', __('Successfully converted invoice to credit note'))
        ;
    }
}
