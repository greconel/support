<?php

namespace App\Notifications\Beacon;

use App\Http\Controllers\Ampp\Implementations\ShowImplementationController;
use App\Models\Implementation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class ImplementationBackUpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Implementation $implementation,
    ) {}

    public function via($notifiable): array
    {
        $channels = ['mail'];

        if (config('services.teams.webhook_url')) {
            $channels[] = MicrosoftTeamsChannel::class;
        }

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Implementation recovered: :name', ['name' => $this->implementation->name]))
            ->greeting(__('Implementation Back Online'))
            ->line(__(':name is responding again.', ['name' => $this->implementation->name]))
            ->action(__('View Implementation'), action(ShowImplementationController::class, $this->implementation))
            ->line(__('This is an automated alert from AMPP Beacon.'));
    }

    public function toMicrosoftTeams($notifiable): MicrosoftTeamsMessage
    {
        return MicrosoftTeamsMessage::create()
            ->to(config('services.teams.webhook_url'))
            ->type('success')
            ->title(__('Implementation recovered: :name', ['name' => $this->implementation->name]))
            ->content(__(':name is responding again.', ['name' => $this->implementation->name]))
            ->button(__('View in AMPP'), action(ShowImplementationController::class, $this->implementation));
    }
}
