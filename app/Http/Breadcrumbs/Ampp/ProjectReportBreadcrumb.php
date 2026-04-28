<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexProjectReport', function (BreadcrumbTrail $trail, string $from, string $till){
    $trail->push(
        __('Project reports'),
        action(\App\Http\Controllers\Ampp\ProjectReports\IndexProjectReportsController::class, ['from' => $from, 'till' => $till])
    );
});

Breadcrumbs::for('detailedTimeReport', function (BreadcrumbTrail $trail, string $from, string $till, string $name){
    $trail
        ->parent('indexProjectReport', $from, $till, $name)
        ->push(__('Detailed time report for :name', ['name' => $name]))
    ;
});

Breadcrumbs::for('prepareInvoicing', function (BreadcrumbTrail $trail){
    $trail->push(__('Prepare invoicing'));
});
