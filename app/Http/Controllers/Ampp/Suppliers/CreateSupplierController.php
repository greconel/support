<?php

namespace App\Http\Controllers\Ampp\Suppliers;

use App\Classes\CountriesHelper;
use App\Http\Controllers\Controller;
use App\Models\InvoiceCategory;
use App\Models\Supplier;
use Illuminate\Support\Facades\Cache;

class CreateSupplierController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Supplier::class);

        $countriesHelper = new CountriesHelper();

        $countries = Cache::remember('countries', 60*60, fn() => $countriesHelper->getLocaleList()->toArray());

        $invoiceCategories = InvoiceCategory::all()->pluck('name', 'id')->prepend('', '')->toArray();

        return view('ampp.suppliers.create', [
            'countries' => $countries,
            'invoiceCategories' => $invoiceCategories
        ]);
    }
}
