<?php

namespace App\Http\Controllers\Ampp\Deals;

use App\Http\Controllers\Controller;
use App\Models\Deal;

class IndexDealBoardController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Deal::class);

        return view('ampp.deals.board');
    }
}
