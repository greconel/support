<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexUser', function (BreadcrumbTrail $trail){
    $trail->push(__('Users'), action(\App\Http\Controllers\Admin\Users\IndexUserController::class));
});

Breadcrumbs::for('createUser', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexUser')
        ->push(__('Create new user'), action(\App\Http\Controllers\Admin\Users\CreateUserController::class))
    ;
});

Breadcrumbs::for('showUser', function (BreadcrumbTrail $trail, \App\Models\User $user){
    $trail
        ->parent('indexUser')
        ->push($user->name, action(\App\Http\Controllers\Admin\Users\ShowUserController::class, $user))
    ;
});

Breadcrumbs::for('editUser', function (BreadcrumbTrail $trail, \App\Models\User $user){
    $trail
        ->parent('indexUser')
        ->push(
            __('Edit user :name', ['name' => $user->name]),
            action(\App\Http\Controllers\Admin\Users\EditUserController::class, $user)
        )
    ;
});
