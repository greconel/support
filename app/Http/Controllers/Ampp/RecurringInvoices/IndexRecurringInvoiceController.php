<?php

namespace App\Http\Controllers\Ampp\RecurringInvoices;

use App\DataTables\Ampp\RecurringInvoiceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class IndexRecurringInvoiceController extends Controller
{
    public function __invoke(Request $request, RecurringInvoiceDataTable $dataTable)
    {
        $this->authorize('create', Invoice::class);

        return $dataTable
            ->with([
                'period' => $request->input('period'),
                'is_active' => $request->input('is_active'),
            ])
            ->render('ampp.recurring-invoices.index');
    }
}
