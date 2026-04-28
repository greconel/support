<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexMyHelpMessage', function (BreadcrumbTrail $trail){
    $trail->push(__('Help messages'), action(\App\Http\Controllers\Ampp\HelpMessages\IndexHelpMessageController::class));
});

Breadcrumbs::for('showMyHelpMessage', function (BreadcrumbTrail $trail, \App\Models\HelpMessage $helpMessage){
    $trail
        ->parent('indexMyHelpMessage')
        ->push($helpMessage->title, action(\App\Http\Controllers\Ampp\HelpMessages\ShowHelpMessageController::class, $helpMessage))
    ;
});
