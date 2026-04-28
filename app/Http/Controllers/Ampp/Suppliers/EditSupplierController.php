<?php

namespace App\Http\Controllers\Ampp\Suppliers;

use App\Classes\CountriesHelper;
use App\Http\Controllers\Controller;
use App\Models\InvoiceCategory;
use App\Models\Supplier;
use Illuminate\Support\Facades\Cache;

class EditSupplierController extends Controller
{
    public function __invoke(Supplier $supplier)
    {
        $this->authorize('update', $supplier);

        $countriesHelper = new CountriesHelper();

        $countries = Cache::remember('countries', 60*60, fn() => $countriesHelper->getLocaleList()->toArray());

        $invoiceCategories = InvoiceCategory::all()->pluck('name', 'id')->prepend('', '')->toArray();

        return view('ampp.suppliers.edit', [
            'supplier' => $supplier,
            'countries' => $countries,
            'invoiceCategories' => $invoiceCategories
        ]);
    }
}
