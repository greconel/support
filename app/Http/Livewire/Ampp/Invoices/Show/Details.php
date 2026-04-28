<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\On;
use Livewire\Component;

class Details extends Component
{
    public Invoice $invoice;
    public string $status = '';
    public ?string $paidAt = null;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->paidAt = $this->invoice->paid_at?->format('Y-m-d');
    }

    #[On('refreshInvoice')]
    public function refreshInvoice()
    {
        $this->invoice->refresh();
    }

    public function updateStatus()
    {
        $this->validate([
            'status' => ['required', new Enum(InvoiceStatus::class)]
        ]);

        $newStatus = InvoiceStatus::from((int) $this->status);

        if ($newStatus === InvoiceStatus::Paid && $this->invoice->remaining_balance > 0) {
            InvoicePayment::create([
                'invoice_id' => $this->invoice->id,
                'user_id' => Auth::id(),
                'amount' => $this->invoice->remaining_balance,
                'paid_at' => now(),
                'payment_method' => PaymentMethod::BankTransfer,
                'notes' => null,
            ]);

            $this->invoice->refresh();

            $this->dispatch('refreshPayments')->to('ampp.invoices.show.payments');
        }

        $this->invoice->status = $this->status;

        $this->invoice->paid_at = $this->invoice->status == InvoiceStatus::Paid
            ? $this->invoice->paid_at ?? now()
            : null;

        $this->invoice->save();

        $this->paidAt = $this->invoice->paid_at?->format('Y-m-d');

        $this->reset('status');
    }

    public function updatedPaidAt()
    {
        $this->validate([
            'paidAt' => ['nullable', 'date']
        ]);

        $this->invoice->update(['paid_at' => empty($this->paidAt) ? null : $this->paidAt]);

        if ($this->invoice->payments()->count() === 1
            && $this->invoice->payments()->first()->amount === $this->invoice->amount_with_vat
        ) {
            $this->invoice->payments()->first()->update(['paid_at' => $this->invoice->paid_at]);
        }

        $this->dispatch('refreshPayments')->to('ampp.invoices.show.payments');
    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.details');
    }
}
