<?php

namespace App\Http\Controllers\Ampp\Quotations;

use App\Http\Controllers\Controller;
use App\Models\Quotation;

class ShowQuotationController extends Controller
{
    public function __invoke(Quotation $quotation)
    {
        $this->authorize('view', $quotation);

        return view('ampp.quotations.show', [
            'quotation' => $quotation
        ]);
    }
}
