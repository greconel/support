<?php

namespace App\Http\Controllers\Ampp\Suppliers;

use App\DataTables\Ampp\SupplierDataTable;
use App\Http\Controllers\Controller;
use App\Models\Supplier;

class IndexSupplierController extends Controller
{
    public function __invoke(SupplierDataTable $dataTable)
    {
        $this->authorize('viewAny', Supplier::class);

        return $dataTable->render('ampp.suppliers.index');
    }
}
