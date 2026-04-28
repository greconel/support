<?php

namespace App\Http\Livewire\Ampp\Invoices;

use App\Enums\InvoiceType;
use App\Http\Livewire\Ampp\Billing\BillingLines;
use App\Models\Invoice;

class Lines extends BillingLines
{
    public Invoice $invoice;

    public function mount(string $sessionKey, Invoice $invoice = null)
    {
        $this->invoice = $invoice;
        $this->getLines();

        parent::mount($sessionKey);
    }

    public function getLines()
    {
        $this->lines = $this->invoice->billingLines
            ->map(function($l){
                $l->amount = floatval($l->amount);
                $l->subtotal = floatval($l->subtotal);
                $l->price = floatval($l->price);
                $l->vat = floatval($l->vat);
                return $l;
            })
            ->toArray();
    }

    public function save()
    {
        if ($this->invoice->type == InvoiceType::Credit){
            $this->rules['lines.*.subtotal'] = ['numeric', 'required_if:lines.*.type,text'];
        }

        $this->validate();

        // remove old lines
        $this->invoice->billingLines()->delete();

        // save new lines
        $this->invoice->billingLines()->createMany($this->lines);

        // Update quotation totals amounts

        // VAT included
        $this->invoice->amount_with_vat = $this->CalcTotal(true);

        // VAT excluded
        $this->invoice->amount = $this->CalcTotal();

        // save quotation
        $this->invoice->save();

        session()->forget($this->sessionKey);

        return redirect()->action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $this->invoice);
    }

    public function generatePdf()
    {
        $previewInvoice = $this->invoice;
        $previewInvoice->amount = $this->totalPrice ?? 0;
        $previewInvoice->amount_with_vat = $this->calcTotal(true) ?? 0;

        $this->pdf = $previewInvoice->generatePdf($this->lines, true);

        $this->dispatch('openPdfModal');
    }
}
