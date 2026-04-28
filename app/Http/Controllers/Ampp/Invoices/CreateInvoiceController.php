<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceCategory;

class CreateInvoiceController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Invoice::class);

        $clients = Client::all()->pluck('full_name_with_company', 'id')->toArray();
        $types = InvoiceType::cases();
        $invoiceCategories = InvoiceCategory::all()->pluck('name', 'id')->prepend('', '')->toArray();
        $clientCategoryMap = Client::whereNotNull('invoice_category_id')->pluck('invoice_category_id', 'id')->toArray();

        return view('ampp.invoices.create', [
            'clients' => $clients,
            'types' => $types,
            'invoiceCategories' => $invoiceCategories,
            'clientCategoryMap' => $clientCategoryMap,
        ]);
    }
}
