<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class IndexClearfactsBulkInvoiceController extends Controller
{
    public function __invoke()
    {
        $invoices = Invoice::notUploadedToClearfacts()
            ->disabledForClearfacts(false)
            ->orderByDesc('number')
            ->get();

        return view('ampp.invoices.bulk', [
            'invoices' => $invoices
        ]);
    }
}
