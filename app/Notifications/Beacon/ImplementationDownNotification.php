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

class ImplementationDownNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Implementation $implementation,
        public int $consecutiveFailures = 2,
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
            ->subject(__('Implementation down: :name', ['name' => $this->implementation->name]))
            ->error()
            ->greeting(__('Implementation Offline'))
            ->line(__(':name has failed :count consecutive heartbeat checks.', [
                'name' => $this->implementation->name,
                'count' => $this->consecutiveFailures,
            ]))
            ->line(__('URL: :url', ['url' => $this->implementation->app_url]))
            ->action(__('View Implementation'), action(ShowImplementationController::class, $this->implementation))
            ->line(__('This is an automated alert from AMPP Beacon.'));
    }

    public function toMicrosoftTeams($notifiable): MicrosoftTeamsMessage
    {
        return MicrosoftTeamsMessage::create()
            ->to(config('services.teams.webhook_url'))
            ->type('error')
            ->title(__('Implementation down: :name', ['name' => $this->implementation->name]))
            ->content(__(':name has failed :count consecutive heartbeat checks. URL: :url', [
                'name' => $this->implementation->name,
                'count' => $this->consecutiveFailures,
                'url' => $this->implementation->app_url,
            ]))
            ->button(__('View in AMPP'), action(ShowImplementationController::class, $this->implementation));
    }
}
