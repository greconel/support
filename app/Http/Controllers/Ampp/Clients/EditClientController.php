<?php

namespace App\Http\Controllers\Ampp\Clients;

use App\Classes\CountriesHelper;
use App\Enums\ClientType;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InvoiceCategory;
use Illuminate\Support\Facades\Cache;

class EditClientController extends Controller
{
    public function __invoke($id)
    {
        $client = Client::withTrashed()->findOrFail($id);

        $this->authorize('update', $client);

        $countriesHelper = new CountriesHelper();

        $countries = Cache::remember('countries', 60*60, fn() => $countriesHelper->getLocaleList()->toArray());

        $types = collect(ClientType::cases())
            ->mapWithKeys(fn(ClientType $type) => [$type->value => $type->label()])
            ->toArray();

        $invoiceCategories = InvoiceCategory::all()->pluck('name', 'id')->prepend('', '')->toArray();

        return view('ampp.clients.edit', [
            'client' => $client,
            'countries' => $countries,
            'types' => $types,
            'invoiceCategories' => $invoiceCategories
        ]);
    }
}
