<?php

namespace App\Http\Controllers\Ampp\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Products\StoreProductRequest;
use App\Models\Product;

class StoreProductController extends Controller
{
    public function __invoke(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $product = Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
        ]);

        if ($request->has('images')) {
            foreach ($request->input('images') as $image) {
                $product
                    ->addMedia(storage_path('app/livewire-tmp/' . $image['path']))
                    ->usingName($image['name'])
                    ->toMediaCollection('images', 'private')
                ;
            }
        }

        return redirect()
            ->action(IndexProductController::class)
            ->with('success', __('Successfully created new product'))
        ;
    }
}
