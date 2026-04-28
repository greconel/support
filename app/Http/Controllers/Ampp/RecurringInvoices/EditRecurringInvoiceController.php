<?php

namespace App\Http\Controllers\Ampp\RecurringInvoices;

use App\Enums\RecurringInvoicePeriod;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\RecurringInvoice;

class EditRecurringInvoiceController extends Controller
{
    public function __invoke(RecurringInvoice $recurringInvoice)
    {
        $this->authorize('create', Invoice::class);

        $clients = Client::all()->pluck('full_name_with_company', 'id')->toArray();
        $periods = RecurringInvoicePeriod::cases();

        return view('ampp.recurring-invoices.edit', [
            'recurringInvoice' => $recurringInvoice,
            'clients' => $clients,
            'periods' => $periods,
        ]);
    }
}
