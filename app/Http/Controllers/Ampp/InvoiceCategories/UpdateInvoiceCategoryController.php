<?php

namespace App\Http\Controllers\Ampp\InvoiceCategories;

use App\Http\Controllers\Controller;
use App\Models\InvoiceCategory;
use Illuminate\Http\Request;

class UpdateInvoiceCategoryController extends Controller
{
    public function __invoke(Request $request, InvoiceCategory $invoiceCategory)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $invoiceCategory->update(['name' => $request->input('name')]);

        session()->flash('success', __('Invoice category updated'));

        return redirect()->action(IndexInvoiceCategoryController::class);
    }
}
