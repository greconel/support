<?php

namespace App\Http\Controllers\Ampp\RecurringInvoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\RecurringInvoice;

class EditRecurringInvoiceLinesController extends Controller
{
    public function __invoke(RecurringInvoice $recurringInvoice)
    {
        $this->authorize('create', Invoice::class);

        return view('ampp.recurring-invoices.lines', [
            'recurringInvoice' => $recurringInvoice,
        ]);
    }
}
