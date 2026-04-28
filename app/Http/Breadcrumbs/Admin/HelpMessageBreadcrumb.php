<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexHelpMessage', function (BreadcrumbTrail $trail){
    $trail->push(__('Help messages'), action(\App\Http\Controllers\Admin\HelpMessages\IndexHelpMessageController::class));
});

Breadcrumbs::for('showHelpMessage', function (BreadcrumbTrail $trail, \App\Models\HelpMessage $helpMessage){
    $trail
        ->parent('indexHelpMessage')
        ->push($helpMessage->title, action(\App\Http\Controllers\Admin\HelpMessages\ShowHelpMessageController::class, $helpMessage))
    ;
});
