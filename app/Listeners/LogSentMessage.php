<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;

class LogSentMessage
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
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        if (array_key_exists('email', $event->data)){
            $event->data['email']->disableLogging();

            $event->data['email']->update([
                'status' => 'sent',
                'content_full' => $event->message->getBody()
            ]);
        }
    }
}
