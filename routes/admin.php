<?php

Route::prefix('users')->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\Users\IndexUserController::class);
    Route::get('create', \App\Http\Controllers\Admin\Users\CreateUserController::class);
    Route::post('/', \App\Http\Controllers\Admin\Users\StoreUserController::class);
    Route::get('export', \App\Http\Controllers\Admin\Users\ExportUsersController::class);
    Route::get('{user}', \App\Http\Controllers\Admin\Users\ShowUserController::class);
    Route::get('{user}/edit', \App\Http\Controllers\Admin\Users\EditUserController::class);
    Route::patch('{user}', \App\Http\Controllers\Admin\Users\UpdateUserController::class);
    Route::delete('{user}', \App\Http\Controllers\Admin\Users\DestroyUserController::class);
    Route::patch('{user}/archive', \App\Http\Controllers\Admin\Users\ArchiveUserController::class);
    Route::patch('{user}/restore', \App\Http\Controllers\Admin\Users\RestoreUserController::class);
    Route::get('{user}/impersonate', \App\Http\Controllers\Admin\Users\ImpersonateUserController::class);
});

Route::prefix('roles')->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\Roles\IndexRoleController::class);
    Route::get('create', \App\Http\Controllers\Admin\Roles\CreateRoleController::class);
    Route::post('/', \App\Http\Controllers\Admin\Roles\StoreRoleController::class);
    Route::get('{role}/edit', \App\Http\Controllers\Admin\Roles\EditRoleController::class);
    Route::patch('{role}', \App\Http\Controllers\Admin\Roles\UpdateRoleController::class);
    Route::delete('{role}', \App\Http\Controllers\Admin\Roles\DestroyRoleController::class);
});

Route::prefix('permissions')->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\Permissions\IndexPermissionController::class);
    Route::get('create', \App\Http\Controllers\Admin\Permissions\CreatePermissionController::class);
    Route::post('/', \App\Http\Controllers\Admin\Permissions\StorePermissionController::class);
    Route::get('{permission}/edit', \App\Http\Controllers\Admin\Permissions\EditPermissionController::class);
    Route::patch('{permission}', \App\Http\Controllers\Admin\Permissions\UpdatePermissionController::class);
    Route::delete('{permission}', \App\Http\Controllers\Admin\Permissions\DestroyPermissionController::class);
});

Route::prefix('passport-clients')->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\PassportClients\IndexPassportClientController::class);
    Route::get('create', \App\Http\Controllers\Admin\PassportClients\CreatePassportClientController::class);
    Route::post('/', \App\Http\Controllers\Admin\PassportClients\StorePassportClientController::class);
    Route::get('{passportClient}/edit', \App\Http\Controllers\Admin\PassportClients\EditPassportClientController::class);
    Route::patch('{passportClient}', \App\Http\Controllers\Admin\PassportClients\UpdatePassportClientController::class);
    Route::delete('{passportClient}', \App\Http\Controllers\Admin\PassportClients\DestroyPassportClientController::class);
});

Route::prefix('activity-logs')->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\ActivityLogs\IndexActivityLogController::class);
    Route::get('{activityLog}', \App\Http\Controllers\Admin\ActivityLogs\ShowActivityLogController::class);
});

Route::prefix('login-logs')->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\LoginLogs\IndexLoginLogController::class);
    Route::get('{loginLog}', \App\Http\Controllers\Admin\LoginLogs\ShowLoginLogController::class);
});

Route::prefix('help-messages')->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\HelpMessages\IndexHelpMessageController::class);
    Route::get('{helpMessage}', \App\Http\Controllers\Admin\HelpMessages\ShowHelpMessageController::class);
});

Route::get('laravel-logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])
    ->middleware('permission:view system logs');
Route::get('artisan', \App\Http\Controllers\Admin\Artisan\IndexArtisanController::class);
