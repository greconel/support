<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexLoginLog', function (BreadcrumbTrail $trail){
    $trail->push(__('Activity logs'), action(\App\Http\Controllers\Admin\LoginLogs\IndexLoginLogController::class));
});

Breadcrumbs::for('showLoginLog', function (BreadcrumbTrail $trail, \App\Models\LoginLog $loginLog){
    $trail
        ->parent('indexLoginLog')
        ->push(__('login log overview'), action(\App\Http\Controllers\Admin\LoginLogs\ShowLoginLogController::class, $loginLog))
    ;
});
