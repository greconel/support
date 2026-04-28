<?php

namespace App\Http\Controllers\Ampp\RecurringInvoices;

use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\RecurringInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateInvoicesController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('create', Invoice::class);

        $validated = $request->validate([
            'recurring_invoice_ids' => ['required', 'array', 'min:1'],
            'recurring_invoice_ids.*' => ['required', 'integer', 'exists:recurring_invoices,id'],
            'invoice_date' => ['required', 'date_format:d/m/Y'],
            'expiration_date' => ['required', 'date_format:d/m/Y'],
        ]);

        $invoiceDate = Carbon::createFromFormat('d/m/Y', $validated['invoice_date']);
        $expirationDate = Carbon::createFromFormat('d/m/Y', $validated['expiration_date']);

        $generatedInvoiceIds = [];

        foreach ($validated['recurring_invoice_ids'] as $recurringInvoiceId) {
            $recurringInvoice = RecurringInvoice::with('billingLines')->findOrFail($recurringInvoiceId);

            // Sync product prices before generating
            $recurringInvoice->syncProductPrices();
            $recurringInvoice->refresh();

            // Create draft invoice
            $invoice = new Invoice([
                'type' => InvoiceType::Debit,
                'client_id' => $recurringInvoice->client_id,
                'custom_created_at' => $invoiceDate,
                'expiration_date' => $expirationDate,
                'notes' => $recurringInvoice->notes,
                'po_number' => $recurringInvoice->po_number,
                'invoice_category_id' => $recurringInvoice->client?->invoice_category_id,
            ]);

            $invoice->save();

            // Copy billing lines
            $totalAmount = 0;
            $totalAmountWithVat = 0;

            foreach ($recurringInvoice->billingLines as $line) {
                $newLine = $line->replicate();
                $newLine->model_type = Invoice::class;
                $newLine->model_id = $invoice->id;
                $newLine->save();

                if ($line->type === 'text' && $line->subtotal) {
                    $totalAmount += $line->subtotal;
                    $totalAmountWithVat += $line->subtotal * (1 + ($line->vat / 100));
                }
            }

            $invoice->update([
                'amount' => $totalAmount,
                'amount_with_vat' => $totalAmountWithVat,
            ]);

            $generatedInvoiceIds[] = $invoice->id;

            $recurringInvoice->update(['last_generated_at' => now()]);
        }

        return redirect()
            ->action(\App\Http\Controllers\Ampp\Invoices\IndexInvoiceController::class, [
                'invoiceids' => implode(',', $generatedInvoiceIds),
            ])
            ->with('success', __(':count invoice(s) generated as draft.', ['count' => count($generatedInvoiceIds)]));
    }
}
