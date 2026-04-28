<?php

namespace App\Actions\Invoices;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Support\Carbon;

class CalculateOutstandingAmountAction
{
    public function execute(?Carbon $expiresFrom = null, ?Carbon $expiresTill = null, bool $vat = true, bool $returnInvoiceIDs = false): float|array
    {
        $amountColumn = $vat ? 'amount_with_vat' : 'amount';

        $invoices = Invoice::where('type', '=', InvoiceType::Debit)
            ->where('status', '!=', InvoiceStatus::Paid)
            ->where('status', '!=', InvoiceStatus::Draft);

        if ($expiresFrom) {
            $invoices->where('expiration_date', '>=', $expiresFrom);
        }

        if ($expiresTill) {
            $invoices->where('expiration_date', '<=', $expiresTill);
        }

        if ($returnInvoiceIDs) {
            return $invoices->pluck('id')->toArray();
        }

        $totalInvoiceAmount = (clone $invoices)->sum($amountColumn);

        $invoiceIds = (clone $invoices)->pluck('id');
        $totalPayments = InvoicePayment::whereIn('invoice_id', $invoiceIds)->sum('amount');

        if (!$vat && $totalPayments > 0) {
            $totalInclVat = (clone $invoices)->sum('amount_with_vat');
            $totalExclVat = (clone $invoices)->sum('amount');

            if ($totalInclVat > 0) {
                $ratio = $totalExclVat / $totalInclVat;
                $totalPayments = $totalPayments * $ratio;
            }
        }

        return max(0, $totalInvoiceAmount - $totalPayments);
    }
}
