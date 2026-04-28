<?php

namespace App\Http\Controllers\Ampp\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class DestroyProductController extends Controller
{
    public function __invoke(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        session()->flash('success', __('Goodbye product! 😥'));

        return redirect()->action(IndexProductController::class);
    }
}
