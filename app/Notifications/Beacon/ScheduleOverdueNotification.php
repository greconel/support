<?php

namespace App\Notifications\Beacon;

use App\Http\Controllers\Ampp\Implementations\ShowImplementationController;
use App\Models\ImplementationSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class ScheduleOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ImplementationSchedule $schedule,
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
        $impl = $this->schedule->implementation;

        return (new MailMessage)
            ->subject(__('Scheduled task overdue: :command on :name', [
                'command' => $this->schedule->command,
                'name' => $impl->name,
            ]))
            ->error()
            ->greeting(__('Scheduled Task Overdue'))
            ->line(__('The command **:command** on **:name** has not checked in on time.', [
                'command' => $this->schedule->command,
                'name' => $impl->name,
            ]))
            ->line(__('Schedule: :expression', ['expression' => $this->schedule->expression]))
            ->line(__('Last run: :time', [
                'time' => $this->schedule->last_finished_at
                    ? $this->schedule->last_finished_at->format('d/m/Y H:i:s')
                    : __('Never'),
            ]))
            ->action(__('View Implementation'), action(ShowImplementationController::class, $impl))
            ->line(__('This is an automated alert from AMPP Beacon.'));
    }

    public function toMicrosoftTeams($notifiable): MicrosoftTeamsMessage
    {
        $impl = $this->schedule->implementation;

        return MicrosoftTeamsMessage::create()
            ->to(config('services.teams.webhook_url'))
            ->type('warning')
            ->title(__('Scheduled task overdue on :name', ['name' => $impl->name]))
            ->content(__('**:command** (:expression) has not checked in on time. Last run: :time', [
                'command' => $this->schedule->command,
                'expression' => $this->schedule->expression,
                'time' => $this->schedule->last_finished_at
                    ? $this->schedule->last_finished_at->diffForHumans()
                    : __('Never'),
            ]))
            ->button(__('View in AMPP'), action(ShowImplementationController::class, $impl));
    }
}
