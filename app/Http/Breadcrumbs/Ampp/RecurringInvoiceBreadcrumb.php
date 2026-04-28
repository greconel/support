<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexRecurringInvoice', function (BreadcrumbTrail $trail) {
    $trail->push(__('Recurring invoices'), action(\App\Http\Controllers\Ampp\RecurringInvoices\IndexRecurringInvoiceController::class));
});

Breadcrumbs::for('createRecurringInvoice', function (BreadcrumbTrail $trail) {
    $trail
        ->parent('indexRecurringInvoice')
        ->push(__('Create recurring invoice'), action(\App\Http\Controllers\Ampp\RecurringInvoices\CreateRecurringInvoiceController::class))
    ;
});

Breadcrumbs::for('showRecurringInvoice', function (BreadcrumbTrail $trail, \App\Models\RecurringInvoice $recurringInvoice) {
    $trail
        ->parent('indexRecurringInvoice')
        ->push(
            $recurringInvoice->name,
            action(\App\Http\Controllers\Ampp\RecurringInvoices\ShowRecurringInvoiceController::class, $recurringInvoice)
        )
    ;
});

Breadcrumbs::for('editRecurringInvoice', function (BreadcrumbTrail $trail, \App\Models\RecurringInvoice $recurringInvoice) {
    $trail
        ->parent('showRecurringInvoice', $recurringInvoice)
        ->push(__('Edit'))
    ;
});

Breadcrumbs::for('editRecurringInvoiceLines', function (BreadcrumbTrail $trail, \App\Models\RecurringInvoice $recurringInvoice) {
    $trail
        ->parent('showRecurringInvoice', $recurringInvoice)
        ->push(__('Edit lines'))
    ;
});
