<?php

namespace App\Http\Controllers\Ampp\InvoiceCategories;

use App\Http\Controllers\Controller;
use App\Models\InvoiceCategory;

class DestroyInvoiceCategoryController extends Controller
{
    public function __invoke(InvoiceCategory $invoiceCategory)
    {
        $invoiceCategory->delete();

        session()->flash('success', __('Invoice category deleted'));

        return redirect()->action(IndexInvoiceCategoryController::class);
    }
}
