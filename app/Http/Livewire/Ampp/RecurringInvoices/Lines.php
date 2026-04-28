<?php

namespace App\Http\Livewire\Ampp\RecurringInvoices;

use App\Http\Livewire\Ampp\Billing\BillingLines;
use App\Models\Product;
use App\Models\RecurringInvoice;

class Lines extends BillingLines
{
    public RecurringInvoice $recurringInvoice;

    public function mount(string $sessionKey, RecurringInvoice $recurringInvoice = null)
    {
        $this->recurringInvoice = $recurringInvoice;
        $this->getLines();

        parent::mount($sessionKey);
    }

    public function getLines()
    {
        $this->lines = $this->recurringInvoice->billingLines
            ->map(function ($l) {
                $l->amount = floatval($l->amount);
                $l->subtotal = floatval($l->subtotal);
                $l->price = floatval($l->price);
                $l->vat = floatval($l->vat);
                return $l;
            })
            ->toArray();
    }

    public function addLineFromProduct($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        $newLine = [
            'type' => 'text',
            'order' => count($this->lines) + 1,
            'text' => $product->name,
            'amount' => 1,
            'vat' => 21,
            'price' => floatval($product->price),
            'subtotal' => floatval($product->price),
            'discount' => 0,
            'description' => null,
            'product_id' => $product->id,
        ];

        array_push($this->lines, $newLine);

        $this->calcTotal();
    }

    public function save()
    {
        $this->validate();

        // Remove old lines
        $this->recurringInvoice->billingLines()->delete();

        // Save new lines
        $this->recurringInvoice->billingLines()->createMany($this->lines);

        // Update totals
        $this->recurringInvoice->amount_with_vat = $this->calcTotal(true);
        $this->recurringInvoice->amount = $this->calcTotal();
        $this->recurringInvoice->save();

        session()->forget($this->sessionKey);

        return redirect()->action(
            \App\Http\Controllers\Ampp\RecurringInvoices\ShowRecurringInvoiceController::class,
            $this->recurringInvoice
        );
    }

    public function generatePdf()
    {
        // Not applicable for recurring invoices — no PDF preview needed
    }
}
