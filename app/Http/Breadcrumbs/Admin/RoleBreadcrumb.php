<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexRole', function (BreadcrumbTrail $trail){
    $trail->push(__('Roles'), action(\App\Http\Controllers\Admin\Roles\IndexRoleController::class));
});

Breadcrumbs::for('createRole', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexRole')
        ->push(__('Create new role'), action(\App\Http\Controllers\Admin\Roles\CreateRoleController::class))
    ;
});

Breadcrumbs::for('editRole', function (BreadcrumbTrail $trail, \Spatie\Permission\Models\Role $role){
    $trail
        ->parent('indexRole')
        ->push(
            __('Edit role :name', ['name' => $role->name]),
            action(\App\Http\Controllers\Admin\Roles\EditRoleController::class, $role)
        )
    ;
});
