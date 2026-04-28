<?php

namespace App\Http\Controllers\Ampp\Quotations;

use App\DataTables\Ampp\QuotationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;

class IndexQuotationController extends Controller
{
    public function __invoke(Request $request, QuotationDataTable $dataTable)
    {
        $this->authorize('viewAny', Quotation::class);

        return $dataTable
            ->with(['status' => $request->input('status')])
            ->render('ampp.quotations.index')
        ;
    }
}
