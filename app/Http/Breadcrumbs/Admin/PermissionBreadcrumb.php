<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexPermission', function (BreadcrumbTrail $trail){
    $trail->push(__('Permissions'), action(\App\Http\Controllers\Admin\Permissions\IndexPermissionController::class));
});

Breadcrumbs::for('createPermission', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexPermission')
        ->push(
            __('Create new permission'),
            action(\App\Http\Controllers\Admin\Permissions\CreatePermissionController::class)
        )
    ;
});

Breadcrumbs::for('editPermission', function (BreadcrumbTrail $trail, \Spatie\Permission\Models\Permission $permission){
    $trail
        ->parent('indexPermission')
        ->push(
            __('Edit permission :name', ['name' => $permission->name]),
            action(\App\Http\Controllers\Admin\Permissions\EditPermissionController::class, $permission)
        )
    ;
});
