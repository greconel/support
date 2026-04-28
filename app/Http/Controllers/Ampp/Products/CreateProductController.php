<?php

namespace App\Http\Controllers\Ampp\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;

class CreateProductController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Product::class);

        return view('ampp.products.create');
    }
}
