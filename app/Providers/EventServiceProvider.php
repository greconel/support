<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
         \Illuminate\Mail\Events\MessageSent::class => [
             \App\Listeners\LogSentMessage::class,
         ],
         \Illuminate\Auth\Events\Login::class => [
             \App\Listeners\LogSuccessfulLogin::class,
         ],
        // \App\Events\TicketCreated::class => [
        //     \App\Listeners\SendTicketConfirmationToCustomer::class,
        //      \App\Listeners\AnalyseTicketWithAI::class,
        // ],
        // \App\Events\TicketReplyReceived::class => [
        //     \App\Listeners\AnalyseTicketReply::class,
        // ],
        //  \App\Events\AiCorrectionLogCreated::class => [
        //      \App\Listeners\SkillUpdateListener::class,
        //  ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
