<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expense;

class ConfirmExpenseExistsInClearfactsController extends Controller
{
    public function __invoke(Expense $expense)
    {
        $expense->update([
            'sent_to_clearfacts_at' => now(),
        ]);

        session()->flash('success', __('Expense marked as existing in Clearfacts.'));

        return redirect()->action(ShowExpenseController::class, $expense);
    }
}
