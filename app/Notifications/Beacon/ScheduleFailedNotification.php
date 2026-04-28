<?php

namespace App\Notifications\Beacon;

use App\Http\Controllers\Ampp\Implementations\ShowImplementationController;
use App\Models\ImplementationSchedule;
use App\Models\ScheduleExecution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class ScheduleFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ImplementationSchedule $schedule,
        public ScheduleExecution $execution,
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

        $mail = (new MailMessage)
            ->subject(__('Scheduled task failed: :command on :name', [
                'command' => $this->schedule->command,
                'name' => $impl->name,
            ]))
            ->error()
            ->greeting(__('Scheduled Task Failed'))
            ->line(__('The command **:command** on **:name** failed with exit code :code.', [
                'command' => $this->schedule->command,
                'name' => $impl->name,
                'code' => $this->execution->exit_code,
            ]));

        if ($this->execution->output) {
            $mail->line(__('Output:'))
                ->line('```' . substr($this->execution->output, 0, 500) . '```');
        }

        return $mail
            ->action(__('View Implementation'), action(ShowImplementationController::class, $impl))
            ->line(__('This is an automated alert from AMPP Beacon.'));
    }

    public function toMicrosoftTeams($notifiable): MicrosoftTeamsMessage
    {
        $impl = $this->schedule->implementation;

        $content = __('**:command** failed with exit code :code.', [
            'command' => $this->schedule->command,
            'code' => $this->execution->exit_code,
        ]);

        if ($this->execution->output) {
            $content .= "\n\n" . substr($this->execution->output, 0, 300);
        }

        return MicrosoftTeamsMessage::create()
            ->to(config('services.teams.webhook_url'))
            ->type('error')
            ->title(__('Task failed on :name', ['name' => $impl->name]))
            ->content($content)
            ->button(__('View in AMPP'), action(ShowImplementationController::class, $impl));
    }
}
