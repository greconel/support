<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Payments extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    #[On('refreshPayments')]
    public function refreshPayments(): void
    {
        // triggers re-render
    }

    public function deletePayment(int $paymentId)
    {
        $payment = InvoicePayment::where('invoice_id', $this->invoice->id)
            ->where('id', $paymentId)
            ->first();

        if ($payment) {
            $payment->delete();
            $this->dispatch('refreshPayments');
            $this->dispatch('refreshInvoice');
        }
    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.payments', [
            'payments' => $this->invoice->payments()->orderByDesc('paid_at')->get(),
        ]);
    }
}
