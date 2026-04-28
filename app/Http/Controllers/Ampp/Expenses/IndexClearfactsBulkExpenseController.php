<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expense;

class IndexClearfactsBulkExpenseController extends Controller
{
    public function __invoke()
    {
        $expenses = Expense::notUploadedToClearfacts()
            ->disabledForClearfacts(false)
            ->orderByDesc('number')
            ->get();

        return view('ampp.expenses.bulk', [
            'expenses' => $expenses
        ]);
    }
}
