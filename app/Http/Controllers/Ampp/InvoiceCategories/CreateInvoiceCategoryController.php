<?php

namespace App\Http\Controllers\Ampp\InvoiceCategories;

use App\Http\Controllers\Controller;

class CreateInvoiceCategoryController extends Controller
{
    public function __invoke()
    {
        return view('ampp.invoice-categories.create');
    }
}
