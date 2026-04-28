<?php

namespace App\Http\Controllers\Ampp\Deals;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;

class EditDealController extends Controller
{
    public function __invoke(Deal $deal)
    {
        $this->authorize('update', $deal);

        $leads = Client::leads()
            ->get()
            ->pluck('full_name', 'id')
            ->prepend(__('-- Add new lead --'), 'new_lead')
            ->prepend(__('-- None --'), '')
            ->toArray();

        return view('ampp.deals.edit', [
            'deal' => $deal,
            'leads' => $leads
        ]);
    }
}
