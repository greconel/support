<?php

namespace App\Http\Controllers\Ampp\RecurringInvoices;

use App\Enums\RecurringInvoicePeriod;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\RecurringInvoice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreRecurringInvoiceController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('create', Invoice::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'int', Rule::in(Client::all()->pluck('id'))],
            'period' => ['required', 'string', new Enum(RecurringInvoicePeriod::class)],
            'notes' => ['nullable', 'string'],
            'po_number' => ['nullable', 'string', 'max:255'],
        ]);

        $recurringInvoice = RecurringInvoice::create($validated);

        return redirect()->action(ShowRecurringInvoiceController::class, $recurringInvoice);
    }
}
