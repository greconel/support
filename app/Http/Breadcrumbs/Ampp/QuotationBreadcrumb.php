<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexQuotation', function (BreadcrumbTrail $trail){
    $trail->push(__('Quotations'), action(\App\Http\Controllers\Ampp\Quotations\IndexQuotationController::class));
});

Breadcrumbs::for('createQuotation', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexQuotation')
        ->push(__('Create new quotation'), action(\App\Http\Controllers\Ampp\Quotations\CreateQuotationController::class))
    ;
});

Breadcrumbs::for('showQuotation', function (BreadcrumbTrail $trail, \App\Models\Quotation $quotation){
    $trail
        ->parent('indexQuotation')
        ->push(
            $quotation->custom_name,
            action(\App\Http\Controllers\Ampp\Quotations\ShowQuotationController::class, $quotation)
        )
    ;
});

Breadcrumbs::for('editQuotationLines', function (BreadcrumbTrail $trail, \App\Models\Quotation $quotation){
    $trail
        ->parent('showQuotation', $quotation)
        ->push(__('Edit contents'))
    ;
});
