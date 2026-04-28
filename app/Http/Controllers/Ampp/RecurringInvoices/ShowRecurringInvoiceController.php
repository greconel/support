<?php

namespace App\Http\Controllers\Ampp\RecurringInvoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\RecurringInvoice;

class ShowRecurringInvoiceController extends Controller
{
    public function __invoke(RecurringInvoice $recurringInvoice)
    {
        $this->authorize('create', Invoice::class);

        $recurringInvoice->load(['client', 'billingLines']);

        return view('ampp.recurring-invoices.show', [
            'recurringInvoice' => $recurringInvoice,
        ]);
    }
}
