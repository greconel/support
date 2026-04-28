<?php

namespace App\Http\Controllers\Ampp\Quotations;

use App\Exports\QuotationExport;
use App\Http\Controllers\Controller;
use App\Models\Quotation;

class ExportQuotationsController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Quotation::class);

        return (new QuotationExport)->download('quotations.xlsx');
    }
}
