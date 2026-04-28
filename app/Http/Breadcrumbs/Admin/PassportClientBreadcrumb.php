<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexPassportClient', function (BreadcrumbTrail $trail){
    $trail->push(__('Passport clients'), action(\App\Http\Controllers\Admin\PassportClients\IndexPassportClientController::class));
});

Breadcrumbs::for('createPassportClient', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexPassportClient')
        ->push(__('Create new passport client'), action(\App\Http\Controllers\Admin\PassportClients\CreatePassportClientController::class))
    ;
});

Breadcrumbs::for('editPassportClient', function (BreadcrumbTrail $trail, \Laravel\Passport\Client $passportClient){
    $trail
        ->parent('indexPassportClient')
        ->push(
            __('Edit passport client :name', ['name' => $passportClient->name]),
            action(\App\Http\Controllers\Admin\PassportClients\EditPassportClientController::class, $passportClient)
        )
    ;
});
