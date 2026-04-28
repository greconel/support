<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexClient', function (BreadcrumbTrail $trail){
    $trail->push(__('Clients'), action(\App\Http\Controllers\Ampp\Clients\IndexClientController::class));
});

Breadcrumbs::for('createClient', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexClient')
        ->push(__('Create new client'), action(\App\Http\Controllers\Ampp\Clients\CreateClientController::class))
    ;
});

Breadcrumbs::for('showClient', function (BreadcrumbTrail $trail, \App\Models\Client $client){
    $trail
        ->parent('indexClient')
        ->push($client->full_name, action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, $client))
    ;
});

Breadcrumbs::for('editClient', function (BreadcrumbTrail $trail, \App\Models\Client $client){
    $trail
        ->parent('showClient', $client)
        ->push(
            __('Edit'),
            action(\App\Http\Controllers\Ampp\Clients\EditClientController::class, $client)
        )
    ;
});
