<?php

namespace App\Http\Controllers\Ampp\Suppliers;

use App\Exports\SupplierExport;
use App\Http\Controllers\Controller;
use App\Models\Supplier;

class ExportSupplierController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Supplier::class);

        return (new SupplierExport)->download('suppliers.xlsx');
    }
}
