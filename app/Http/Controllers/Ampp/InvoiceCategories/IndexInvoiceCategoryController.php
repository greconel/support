<?php

namespace App\Http\Controllers\Ampp\InvoiceCategories;

use App\DataTables\Admin\InvoiceCategoryDataTable;
use App\Http\Controllers\Controller;

class IndexInvoiceCategoryController extends Controller
{
    public function __invoke(InvoiceCategoryDataTable $dataTable)
    {
        return $dataTable->render('ampp.invoice-categories.index');
    }
}
