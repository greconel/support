<?php

namespace App\Http\Controllers\Ampp\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;

class EditProductController extends Controller
{
    public function __invoke(Product $product)
    {
        $this->authorize('update', $product);

        return view('ampp.products.edit', [
            'product' => $product
        ]);
    }
}
