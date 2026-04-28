<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketReplyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public TicketMessage $message,
        public string $agentName,
    ) {}

    public function build()
    {
        $this
            ->subject("Re: [{$this->ticket->ticket_number}] {$this->ticket->subject}")
            ->to($this->ticket->client->email, $this->ticket->client->full_name)
            ->view('ampp.helpdesk.emails.ticket-reply', [
                'ticket' => $this->ticket,
                'message' => $this->message,
                'body' => $this->message->body_html,
                'agentName' => $this->agentName,
            ])
        ;

        foreach ($this->message->getMedia('attachments') as $attachment) {
            $this->attach($attachment->getPath(), [
                'as' => $attachment->name,
                'mime' => $attachment->mime_type,
            ]);
        }

        return $this;
    }
}
