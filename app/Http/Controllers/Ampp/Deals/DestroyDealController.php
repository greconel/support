<?php

namespace App\Http\Controllers\Ampp\Deals;

use App\Http\Controllers\Controller;
use App\Models\Deal;

class DestroyDealController extends Controller
{
    public function __invoke(Deal $deal)
    {
        $this->authorize('delete', $deal);

        $deal->delete();

        return redirect()->action(IndexDealBoardController::class);
    }
}
