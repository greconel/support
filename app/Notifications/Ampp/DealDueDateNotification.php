<?php

namespace App\Notifications\Ampp;

use App\Http\Controllers\Ampp\Deals\ShowDealController;
use App\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DealDueDateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Deal $deal
    ){}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Reminder for deal'))
            ->greeting($this->deal->name)
            ->line('Your deal is nearing its due date!')
            ->action('Open deal', action(ShowDealController::class, $this->deal))
            ->line(__('This is an automated message.'))
        ;
    }
}
