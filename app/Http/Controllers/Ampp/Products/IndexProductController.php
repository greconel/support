<?php

namespace App\Http\Controllers\Ampp\Products;

use App\DataTables\Ampp\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;

class IndexProductController extends Controller
{
    public function __invoke(ProductDataTable $dataTable)
    {
        $this->authorize('viewAny', Product::class);

        return $dataTable->render('ampp.products.index');
    }
}
