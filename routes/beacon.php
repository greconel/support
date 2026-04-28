<?php

use App\Http\Controllers\Api\Beacon\CronController;
use App\Http\Controllers\Api\Beacon\ErrorController;
use App\Http\Controllers\Api\Beacon\PingController;
use App\Http\Controllers\Api\Beacon\PulseController;
use App\Http\Controllers\Api\Beacon\RegisterController;
use Illuminate\Support\Facades\Route;

// Key verification (no auth needed)
Route::get('verify', [RegisterController::class, 'verify']);

// Authenticated by beacon API key
Route::middleware('beacon')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('pulse', [PulseController::class, 'store']);
    Route::post('cron/started', [CronController::class, 'started']);
    Route::post('cron/finished', [CronController::class, 'finished']);
    Route::post('error', [ErrorController::class, 'store']);
});

// Manual project cron webhooks (authenticated by unique ping token)
Route::post('ping/{token}', [PingController::class, 'store']);
