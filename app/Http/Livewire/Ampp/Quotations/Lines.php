<?php

namespace App\Http\Livewire\Ampp\Quotations;

use App\Http\Livewire\Ampp\Billing\BillingLines;
use App\Models\Quotation;

class Lines extends BillingLines
{
    public Quotation $quotation;

    public function mount(string $sessionKey, Quotation $quotation = null)
    {
        $this->quotation = $quotation;
        $this->getLines();

        parent::mount($sessionKey);
    }

    public function getLines()
    {
        $this->lines = $this->quotation->billingLines
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
        $this->validate();

        // remove old lines
        $this->quotation->billingLines()->delete();

        // save new lines
        $this->quotation->billingLines()->createMany($this->lines);

        // Update quotation totals amounts

        // VAT included
        $this->quotation->amount_with_vat = $this->CalcTotal(true);

        // VAT excluded
        $this->quotation->amount = $this->CalcTotal();

        // save quotation
        $this->quotation->save();

        session()->forget($this->sessionKey);

        return redirect()->action(\App\Http\Controllers\Ampp\Quotations\ShowQuotationController::class, $this->quotation);
    }

    public function generatePdf()
    {
        $previewQuotation = $this->quotation;
        $previewQuotation->amount = $this->totalPrice ?? 0;
        $previewQuotation->amount_with_vat = $this->calcTotal(true) ?? 0;

        $this->pdf = $previewQuotation->generatePdf($this->lines, true);

        $this->dispatch('openPdfModal');
    }
}
