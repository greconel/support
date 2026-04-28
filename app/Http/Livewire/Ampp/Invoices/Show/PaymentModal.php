<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Enums\PaymentMethod;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\On;
use Livewire\Component;

class PaymentModal extends Component
{
    public Invoice $invoice;
    public ?int $paymentId = null;
    public string $amount = '';
    public string $paidAt = '';
    public string $paymentMethod = '';
    public string $notes = '';

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->paidAt = now()->format('Y-m-d');
        $this->paymentMethod = PaymentMethod::BankTransfer->value;
    }

    public function rules(): array
    {
        $maxAmount = $this->paymentId
            ? $this->invoice->remaining_balance + InvoicePayment::find($this->paymentId)?->amount
            : $this->invoice->remaining_balance;

        return [
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $maxAmount],
            'paidAt' => ['required', 'date'],
            'paymentMethod' => ['nullable', new Enum(PaymentMethod::class)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    #[On('editPayment')]
    public function editPayment(int $paymentId)
    {
        $payment = InvoicePayment::where('invoice_id', $this->invoice->id)
            ->where('id', $paymentId)
            ->first();

        if ($payment) {
            $this->paymentId = $payment->id;
            $this->amount = (string) $payment->amount;
            $this->paidAt = $payment->paid_at->format('Y-m-d');
            $this->paymentMethod = $payment->payment_method?->value ?? '';
            $this->notes = $payment->notes ?? '';
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'invoice_id' => $this->invoice->id,
            'user_id' => Auth::id(),
            'amount' => $this->amount,
            'paid_at' => $this->paidAt,
            'payment_method' => $this->paymentMethod ?: null,
            'notes' => $this->notes ?: null,
        ];

        if ($this->paymentId) {
            $payment = InvoicePayment::where('id', $this->paymentId)
                ->where('invoice_id', $this->invoice->id)
                ->first();

            if ($payment) {
                $payment->update($data);
            }
        } else {
            InvoicePayment::create($data);
        }

        $this->reset(['paymentId', 'amount', 'notes']);
        $this->paidAt = now()->format('Y-m-d');
        $this->paymentMethod = PaymentMethod::BankTransfer->value;

        $this->dispatch('close')->self();
        $this->dispatch('refreshPayments')->to('ampp.invoices.show.payments');
        $this->dispatch('refreshInvoice')->to('ampp.invoices.show.details');
    }

    public function resetForm()
    {
        $this->reset(['paymentId', 'amount', 'notes']);
        $this->paidAt = now()->format('Y-m-d');
        $this->paymentMethod = PaymentMethod::BankTransfer->value;
        $this->resetValidation();
    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.payment-modal', [
            'paymentMethods' => PaymentMethod::cases(),
        ]);
    }
}
