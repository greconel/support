<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Actions\Invoices\CalculateOutstandingAmountAction;
use App\DataTables\Ampp\InvoiceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class IndexInvoiceController extends Controller
{
    public function __invoke(
        Request $request,
        InvoiceDataTable $dataTable,
        CalculateOutstandingAmountAction $calculateOutstandingAmountAction
    )
    {
        $this->authorize('viewAny', Invoice::class);

        return $dataTable
            ->with([
                'status' => $request->input('status'),
                'type' => $request->input('type'),
                'invoiceids' => $request->input('invoiceids')
            ])
            ->render('ampp.invoices.index', [
                'outstandingInvoicesNotExpiredInclVat' => $calculateOutstandingAmountAction->execute(now()),
                'outstandingInvoicesNotExpiredExclVat' => $calculateOutstandingAmountAction->execute(now(), vat: false),
                'outstandingInvoicesNotExpiredIDs' => $calculateOutstandingAmountAction->execute(now(), returnInvoiceIDs: true),
                'outstandingInvoicesExpiredForMax30DaysWithVat' => $calculateOutstandingAmountAction->execute(now()->subDays(30), now()),
                'outstandingInvoicesExpiredForMax30DaysExclVat' => $calculateOutstandingAmountAction->execute(now()->subDays(30), now(), false),
                'outstandingInvoicesExpiredForMax30DaysIDs' => $calculateOutstandingAmountAction->execute(now()->subDays(30), now(), returnInvoiceIDs: true),
                'outstandingInvoicesExpiredLongerThen30DaysInclVat' => $calculateOutstandingAmountAction->execute(expiresTill: now()->subDays(30)),
                'outstandingInvoicesExpiredLongerThen30DaysExclVat' => $calculateOutstandingAmountAction->execute(expiresTill: now()->subDays(30), vat: false),
                'outstandingInvoicesExpiredLongerThen30DaysIDs' => $calculateOutstandingAmountAction->execute(expiresTill: now()->subDays(30), returnInvoiceIDs: true),
            ])
        ;
    }
}
