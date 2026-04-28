<?php

namespace App\Providers;

use App\Services\RecommandPeppolClient;
use Illuminate\Support\ServiceProvider;

class RecommandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RecommandPeppolClient::class, function ($app) {
            return new RecommandPeppolClient();
        });
    }

    public function boot(): void
    {
        //
    }
}
