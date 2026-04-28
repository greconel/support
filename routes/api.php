<?php

Route::middleware('client')->group(function (){
    Route::get('revoke-token', \App\Http\Controllers\Api\V1\RevokeTokenController::class);
    Route::post('connection-logs/store', \App\Http\Controllers\Api\V1\ConnectionsLogController::class);

    Route::get('users', \App\Http\Controllers\Api\V1\UserController::class);
});

Route::middleware(['auth:api', 'permission:view ampp zone|view admin zone'])->prefix('internal')->group(function (){
    Route::get('time-registrations/events', [\App\Http\Controllers\Api\Internal\TimeRegistrationController::class, 'events']);
    Route::get('time-registrations/personal-time', [\App\Http\Controllers\Api\Internal\TimeRegistrationController::class, 'personalTime']);
    Route::get('projects/query-with-hours', [\App\Http\Controllers\Api\Internal\ProjectController::class, 'queryWithHours']);
});

