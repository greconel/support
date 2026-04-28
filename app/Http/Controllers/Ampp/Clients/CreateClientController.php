<?php

namespace App\Http\Controllers\Ampp\Clients;

use App\Classes\CountriesHelper;
use App\Enums\ClientType;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InvoiceCategory;
use Illuminate\Support\Facades\Cache;

class CreateClientController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Client::class);

        $countriesHelper = new CountriesHelper();

        $countries = Cache::remember('countries', 60*60, fn() => $countriesHelper->getLocaleList()->toArray());

        $types = collect(ClientType::cases())
            ->mapWithKeys(fn(ClientType $type) => [$type->value => $type->label()])
            ->toArray();

        $invoiceCategories = InvoiceCategory::all()->pluck('name', 'id')->prepend('', '')->toArray();

        return view('ampp.clients.create', [
            'countries' => $countries,
            'types' => $types,
            'invoiceCategories' => $invoiceCategories
        ]);
    }
}
