<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexProduct', function (BreadcrumbTrail $trail){
    $trail->push(__('Products'), action(\App\Http\Controllers\Ampp\Products\IndexProductController::class));
});

Breadcrumbs::for('createProduct', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexProduct')
        ->push(__('Create new product'), action(\App\Http\Controllers\Ampp\Products\CreateProductController::class))
    ;
});

Breadcrumbs::for('editProduct', function (BreadcrumbTrail $trail, \App\Models\Product $product){
    $trail
        ->parent('indexProduct')
        ->push(
            __('Edit :name', ['name' => $product->name]),
            action(\App\Http\Controllers\Ampp\Products\EditProductController::class, $product)
        )
    ;
});
