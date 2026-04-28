<?php

namespace App\Http\Controllers\Ampp\Deals;

use App\Http\Controllers\Controller;
use App\Models\Deal;

class ShowDealController extends Controller
{
    public function __invoke(Deal $deal)
    {
        $this->authorize('view', $deal);

        return view('ampp.deals.show', [
            'deal' => $deal
        ]);
    }
}
