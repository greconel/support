<?php

namespace App\Http\Controllers\Ampp\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Supplier;

class ShowSupplierController extends Controller
{
    public function __invoke(Supplier $supplier)
    {
        $expensesGroupedByYear = Expense::where('supplier_id', '=', $supplier->id)
            ->get()
            ->groupBy(fn(Expense $expense) => $expense->invoice_date->format('Y'))
            ->sortKeysDesc()
        ;

        return view('ampp.suppliers.show', [
            'supplier' => $supplier,
            'expensesGroupedByYear' => $expensesGroupedByYear
        ]);
    }
}
