<?php

namespace App\Mail;

use App\Models\Email;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class CustomMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Email $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function build()
    {
        $this
            ->subject($this->email->subject)
            ->to($this->email->to)
            ->cc($this->email->cc)
            ->bcc($this->email->bcc)
            ->markdown('emails.custom')
        ;

        foreach ($this->email->getMedia('attachments') as $attachment){
            $this->attach($attachment->getPath(), [
                'as' => $attachment->name,
                'mime' => $attachment->mime_type
            ]);
        }

        return $this;
    }
}
