<?php

namespace App\Notifications\Ampp;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class ClearfactsUploadedExpensesNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Collection $expenses
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('We are done uploading to Clearfacts'))
            ->markdown('emails.ampp.clearfactsUploadedExpenses', [
                'expenses' => $this->expenses
            ])
        ;
    }
}
