<?php

use App\Http\Controllers\Api\V1\Clients\IndexClientController;
use App\Http\Controllers\Api\V1\Clients\StoreClientController;
use App\Http\Controllers\Api\V1\Clients\UpdateClientController;
use App\Http\Controllers\Api\V1\Connection\DownloadConnectionLogoController;
use App\Http\Controllers\Api\V1\Connection\GetConnectionController;
use App\Http\Controllers\Api\V1\Connection\GetLatestConnectionController;
use App\Http\Controllers\Api\V1\Invoices\DownloadInvoiceController;
use App\Http\Controllers\Api\V1\Invoices\IndexInvoiceController;
use App\Http\Controllers\Api\V1\Invoices\StoreInvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('invoices')
    ->middleware('client')
    ->group(function () {
        Route::get('/', IndexInvoiceController::class);
        Route::post('create', StoreInvoiceController::class);
    });

Route::get('invoices/{invoice}/download', DownloadInvoiceController::class)
    ->middleware('signed')
    ->name('apiV1.invoices.download');

Route::prefix('clients')
    ->middleware('client')
    ->group(function () {
        Route::get('/', IndexClientController::class);
        Route::post('create', StoreClientController::class);
        Route::patch('edit', UpdateClientController::class);
    });

Route::prefix('connections')
    ->middleware('client')
    ->group(function () {
        Route::get('/', GetConnectionController::class);
        Route::get('latest', GetLatestConnectionController::class);
    });

Route::get('connections/{connection}/download', DownloadConnectionLogoController::class)
    ->middleware('signed')
    ->name('apiV1.connections.download');

Route::get('check-do', function () {

    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toDateTimeString(),
        'server' => config('app.name'),
    ], 200);

});
