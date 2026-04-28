<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Exports\InvoiceExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice;

class ExportInvoicesController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Invoice::class);

        return (new InvoiceExport)->download('invoices.xlsx');
    }
}
