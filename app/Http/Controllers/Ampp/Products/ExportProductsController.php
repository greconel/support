<?php

namespace App\Http\Controllers\Ampp\Products;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ExportProductsController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Product::class);

        return (new ProductExport)->download('products.xlsx');
    }
}
