<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\DataTables\Ampp\ExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class IndexExpenseController extends Controller
{
    public function __invoke(Request $request, ExpenseDataTable $dataTable)
    {
        $this->authorize('viewAny', Expense::class);

        return $dataTable
            ->with(['status' => $request->input('status')])
            ->render('ampp.expenses.index');
    }
}
