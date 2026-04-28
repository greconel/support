<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexSupplier', function (BreadcrumbTrail $trail){
    $trail->push(__('Suppliers'), action(\App\Http\Controllers\Ampp\Suppliers\IndexSupplierController::class));
});

Breadcrumbs::for('createSupplier', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexSupplier')
        ->push(__('Create new supplier'), action(\App\Http\Controllers\Ampp\Suppliers\CreateSupplierController::class))
    ;
});

Breadcrumbs::for('showSupplier', function (BreadcrumbTrail $trail, \App\Models\Supplier $supplier){
    $trail
        ->parent('indexSupplier')
        ->push($supplier->company, action(\App\Http\Controllers\Ampp\Suppliers\ShowSupplierController::class, $supplier))
    ;
});

Breadcrumbs::for('editSupplier', function (BreadcrumbTrail $trail, \App\Models\Supplier $supplier){
    $trail
        ->parent('showSupplier', $supplier)
        ->push(
            __('Edit'),
            action(\App\Http\Controllers\Ampp\Suppliers\EditSupplierController::class, $supplier)
        )
    ;
});
