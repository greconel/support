<?php

namespace App\Http\Controllers\Ampp\RecurringInvoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\RecurringInvoice;

class DestroyRecurringInvoiceController extends Controller
{
    public function __invoke(RecurringInvoice $recurringInvoice)
    {
        $this->authorize('create', Invoice::class);

        $recurringInvoice->delete();

        return redirect()->action(IndexRecurringInvoiceController::class);
    }
}
