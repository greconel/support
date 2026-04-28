<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Exports\ExpenseExport;
use App\Http\Controllers\Controller;
use App\Models\Expense;

class ExportExpensesController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Expense::class);

        return (new ExpenseExport)->download('expenses.xlsx');
    }
}
