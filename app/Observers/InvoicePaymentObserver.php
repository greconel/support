<?php

namespace App\Observers;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\InvoicePayment;

class InvoicePaymentObserver
{
    public function saved(InvoicePayment $payment): void
    {
        $this->updateInvoiceStatus($payment->invoice);
    }

    public function deleted(InvoicePayment $payment): void
    {
        $this->updateInvoiceStatus($payment->invoice);
    }

    private function updateInvoiceStatus(Invoice $invoice): void
    {
        $invoice->refresh();

        if ($invoice->is_fully_paid) {
            if ($invoice->status !== InvoiceStatus::Paid) {
                $invoice->update([
                    'status' => InvoiceStatus::Paid,
                    'paid_at' => $invoice->payments()->latest('paid_at')->value('paid_at') ?? now(),
                ]);
            }
        } elseif ($invoice->is_partially_paid) {
            if ($invoice->status !== InvoiceStatus::PartiallyPaid) {
                $invoice->update([
                    'status' => InvoiceStatus::PartiallyPaid,
                    'paid_at' => null,
                ]);
            }
        } else {
            if (in_array($invoice->status, [InvoiceStatus::Paid, InvoiceStatus::PartiallyPaid])) {
                $invoice->update([
                    'status' => InvoiceStatus::Sent,
                    'paid_at' => null,
                ]);
            }
        }
    }
}
