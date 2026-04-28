<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexInvoice', function (BreadcrumbTrail $trail){
    $trail->push(__('Invoices'), action(\App\Http\Controllers\Ampp\Invoices\IndexInvoiceController::class));
});

Breadcrumbs::for('createInvoice', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexInvoice')
        ->push(__('Create new invoice'), action(\App\Http\Controllers\Ampp\Invoices\CreateInvoiceController::class))
    ;
});

Breadcrumbs::for('showInvoice', function (BreadcrumbTrail $trail, \App\Models\Invoice $invoice){
    $trail
        ->parent('indexInvoice')
        ->push(
            $invoice->custom_name,
            action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $invoice)
        )
    ;
});

Breadcrumbs::for('editInvoiceLines', function (BreadcrumbTrail $trail, \App\Models\Invoice $invoice){
    $trail
        ->parent('showInvoice', $invoice)
        ->push(__('Edit contents'))
    ;
});

Breadcrumbs::for('SelectInvoicesForClearfacts', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexInvoice')
        ->push(__('Invoices Clearfacts bulk upload'))
    ;
});
