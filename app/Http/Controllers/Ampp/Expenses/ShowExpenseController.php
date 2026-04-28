<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expense;

class ShowExpenseController extends Controller
{
    public function __invoke(Expense $expense)
    {
        $this->authorize('view', $expense);

        return view('ampp.expenses.show', [
            'expense' => $expense
        ]);
    }
}
