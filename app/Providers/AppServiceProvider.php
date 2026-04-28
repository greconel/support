<?php

namespace App\Providers;

use App\Models\InvoicePayment;
use App\Models\ProjectActivity;
use App\Models\Ticket;
use App\Models\TimeRegistration;
use App\Observers\InvoicePaymentObserver;
use App\Observers\ProjectActivityObserver;
use App\Observers\TicketObserver;
use App\Observers\TimeRegistrationObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        TimeRegistration::observe(TimeRegistrationObserver::class);
        InvoicePayment::observe(InvoicePaymentObserver::class);
        Ticket::observe(TicketObserver::class);
    }
}
