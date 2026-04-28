<?php

namespace App\Http\Controllers\Ampp\RecurringInvoices;

use App\Enums\RecurringInvoicePeriod;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;

class CreateRecurringInvoiceController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Invoice::class);

        $clients = Client::all()->pluck('full_name_with_company', 'id')->toArray();
        $periods = RecurringInvoicePeriod::cases();

        return view('ampp.recurring-invoices.create', [
            'clients' => $clients,
            'periods' => $periods,
        ]);
    }
}
