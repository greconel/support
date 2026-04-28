<?php

namespace App\Http\Controllers\Ampp\InvoiceCategories;

use App\Http\Controllers\Controller;
use App\Models\InvoiceCategory;

class EditInvoiceCategoryController extends Controller
{
    public function __invoke(InvoiceCategory $invoiceCategory)
    {
        return view('ampp.invoice-categories.edit', [
            'invoiceCategory' => $invoiceCategory,
        ]);
    }
}
