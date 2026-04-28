<?php

namespace App\Http\Controllers\Ampp\Quotations;

use App\Http\Controllers\Controller;
use App\Models\Quotation;

class EditQuotationLinesController extends Controller
{
    public function __invoke(Quotation $quotation)
    {
        $this->authorize('update', $quotation);

        return view('ampp.quotations.lines', [
            'quotation' => $quotation
        ]);
    }
}
