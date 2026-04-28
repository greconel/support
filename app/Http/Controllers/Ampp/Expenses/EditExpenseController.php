<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Enums\VariousTransactionCategory;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\InvoiceCategory;
use App\Models\Supplier;

class EditExpenseController extends Controller
{
    public function __invoke(Expense $expense)
    {
        $this->authorize('update', $expense);

        $suppliers = Supplier::all()
            ->pluck('company_with_full_name', 'id')
            ->prepend('', '')
            ->toArray();

        $variousTransactionCategories = collect(VariousTransactionCategory::cases())
            ->mapWithKeys(fn(VariousTransactionCategory $category) => [$category->value => $category->label()])
            ->sort()
            ->toArray();

        $invoiceCategories = InvoiceCategory::all()->pluck('name', 'id')->prepend('', '')->toArray();

        return view('ampp.expenses.edit', [
            'expense' => $expense,
            'suppliers' => $suppliers,
            'variousTransactionCategories' => $variousTransactionCategories,
            'invoiceCategories' => $invoiceCategories,
        ]);
    }
}
