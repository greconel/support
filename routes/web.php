<?php

Route::middleware('auth')->prefix('private-media')->group(function (){
    Route::get('{media:uuid}', \App\Http\Controllers\Media\ShowMediaController::class);
    Route::get('{media:uuid}/download', \App\Http\Controllers\Media\DownloadMediaController::class);
});

Route::get('/', function () {
    if (!auth()->check()){
        return redirect()->route('login');
    }

    if (auth()->user()->can('view admin zone')){
        return redirect()->action(\App\Http\Controllers\Ampp\DashboardController::class);
    }

    if (auth()->user()->can('view ampp zone')){
        return redirect()->action(\App\Http\Controllers\Ampp\DashboardController::class);
    }

    if (auth()->user()->can('view client zone')){
        return view('guest.welcome');
    }

    return view('guest.welcome');
});
