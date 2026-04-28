<?php

namespace App\Listeners;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = User::find($event->user->getAuthIdentifier());

        if (! $user){
            return;
        }

        $user->loginLogs()->create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'via_remember_me' => auth()->viaRemember()
        ]);
    }
}
