<?php

namespace App\Http\Controllers\Ampp\Quotations;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Quotation;

class CreateQuotationController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Quotation::class);

        $clients = Client::all()->pluck('full_name_with_company', 'id')->toArray();
        $number = Quotation::whereYear('custom_created_at', now()->year)->max('number') + 1;

        return view('ampp.quotations.create', [
            'clients' => $clients,
            'number' => $number
        ]);
    }
}
