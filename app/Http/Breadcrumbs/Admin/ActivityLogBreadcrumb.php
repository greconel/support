<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexActivityLog', function (BreadcrumbTrail $trail){
    $trail->push(__('Activity logs'), action(\App\Http\Controllers\Admin\ActivityLogs\IndexActivityLogController::class));
});

Breadcrumbs::for('showActivityLog', function (BreadcrumbTrail $trail, \Spatie\Activitylog\Models\Activity $activityLog){
    $trail
        ->parent('indexActivityLog')
        ->push(__('Activity log overview'), action(\App\Http\Controllers\Admin\ActivityLogs\ShowActivityLogController::class, $activityLog))
    ;
});
