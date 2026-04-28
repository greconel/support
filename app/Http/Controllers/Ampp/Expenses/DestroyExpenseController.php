<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expense;

class DestroyExpenseController extends Controller
{
    public function __invoke(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        session()->flash('success', __('Goodbye expense! 😥'));

        return redirect()->action(IndexExpenseController::class);
    }
}
