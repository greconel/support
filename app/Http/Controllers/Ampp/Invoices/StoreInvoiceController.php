<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Invoices\StoreInvoiceRequest;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreInvoiceController extends Controller
{
    public function __invoke(StoreInvoiceRequest $request)
    {
        $this->authorize('create', Invoice::class);

        $invoice = new Invoice([
            'type' => $request->input('type'),
            'client_id' => $request->input('client_id'),
            'expiration_date' => Carbon::createFromFormat('d/m/Y', $request->input('expiration_date')),
            'custom_created_at' => Carbon::createFromFormat('d/m/Y', $request->input('custom_created_at')),
            'invoice_category_id' => $request->input('invoice_category_id'),
        ]);

        $invoice->save();

        return redirect()->action(ShowInvoiceController::class, $invoice);
    }
}
