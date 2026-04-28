<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexClientContact', function (BreadcrumbTrail $trail){
    $trail->push(__('Contacts'), action(\App\Http\Controllers\Ampp\ClientContacts\IndexClientContactController::class));
});

Breadcrumbs::for('createClientContact', function (BreadcrumbTrail $trail, \App\Models\Client $client){
    $trail
        ->parent('showClient', $client)
        ->parent('indexClientContact')
        ->push(
            __('Create new contact'),
            action(\App\Http\Controllers\Ampp\ClientContacts\CreateClientContactController::class, $client)
        )
    ;
});

Breadcrumbs::for('showClientContact', function (BreadcrumbTrail $trail, \App\Models\ClientContact $clientContact){
    $trail
        ->parent('showClient', $clientContact->client)
        ->parent('indexClientContact')
        ->push($clientContact->full_name, action(\App\Http\Controllers\Ampp\ClientContacts\ShowClientContactController::class, $clientContact))
    ;
});

Breadcrumbs::for('editClientContact', function (BreadcrumbTrail $trail, \App\Models\ClientContact $clientContact){
    $trail
        ->parent('showClientContact', $clientContact)
        ->push(
            __('Edit'),
            action(\App\Http\Controllers\Ampp\ClientContacts\EditClientContactController::class, $clientContact)
        )
    ;
});
