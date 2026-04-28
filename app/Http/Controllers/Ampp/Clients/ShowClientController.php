<?php

namespace App\Http\Controllers\Ampp\Clients;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Quotation;

class ShowClientController extends Controller
{
    public function __invoke($id)
    {
        $client = Client::withTrashed()->with('projects')->findOrFail($id);

        $this->authorize('view', $client);

        $invoicesGroupedByYear = Invoice::where('client_id', '=', $client->id)
            ->get()
            ->groupBy(fn(Invoice $invoice) => $invoice->custom_created_at->format('Y'))
            ->sortKeysDesc()
        ;

        $quotationsGroupedByYear = Quotation::where('client_id', '=', $client->id)
            ->get()
            ->groupBy(fn(Quotation $quotation) => $quotation->custom_created_at->format('Y'))
            ->sortKeysDesc()
        ;

        return view('ampp.clients.show', [
            'client' => $client,
            'invoicesGroupedByYear' => $invoicesGroupedByYear,
            'quotationsGroupedByYear' => $quotationsGroupedByYear
        ]);
    }
}
