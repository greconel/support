<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Enums\VariousTransactionCategory;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\InvoiceCategory;
use App\Models\Supplier;

class CreateExpenseController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Expense::class);

        $suppliers = Supplier::all()
            ->pluck('company_with_full_name', 'id')
            ->prepend('', '')
            ->toArray();

        $variousTransactionCategories = collect(VariousTransactionCategory::cases())
            ->mapWithKeys(fn(VariousTransactionCategory $category) => [$category->value => $category->label()])
            ->sort()
            ->toArray();

        $invoiceCategories = InvoiceCategory::all()->pluck('name', 'id')->prepend('', '')->toArray();
        $supplierCategoryMap = Supplier::whereNotNull('invoice_category_id')->pluck('invoice_category_id', 'id')->toArray();

        return view('ampp.expenses.create', [
            'suppliers' => $suppliers,
            'variousTransactionCategories' => $variousTransactionCategories,
            'invoiceCategories' => $invoiceCategories,
            'supplierCategoryMap' => $supplierCategoryMap,
        ]);
    }
}
