<?php

namespace App\Notifications\Beacon;

use App\Http\Controllers\Ampp\Implementations\ShowImplementationController;
use App\Models\Implementation;
use App\Models\ImplementationError;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class ImplementationErrorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Implementation $implementation,
        public ImplementationError $error,
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
        $mail = (new MailMessage)
            ->subject(__(':level on :name: :message', [
                'level' => ucfirst($this->error->level->value),
                'name' => $this->implementation->name,
                'message' => substr($this->error->message, 0, 80),
            ]))
            ->error()
            ->greeting(__('Application Error — :name', ['name' => $this->implementation->name]))
            ->line(__('**:class**: :message', [
                'class' => $this->error->exception_class ? class_basename($this->error->exception_class) : 'Error',
                'message' => $this->error->message,
            ]));

        if ($this->error->file) {
            $mail->line(__('File: :file::line', [
                'file' => $this->error->file,
                'line' => $this->error->line,
            ]));
        }

        return $mail
            ->action(__('View Implementation'), action(ShowImplementationController::class, $this->implementation))
            ->line(__('This is an automated alert from AMPP Beacon.'));
    }

    public function toMicrosoftTeams($notifiable): MicrosoftTeamsMessage
    {
        return MicrosoftTeamsMessage::create()
            ->to(config('services.teams.webhook_url'))
            ->type('error')
            ->title(__(':level on :name', [
                'level' => ucfirst($this->error->level->value),
                'name' => $this->implementation->name,
            ]))
            ->content(__('**:class**: :message', [
                'class' => $this->error->exception_class ? class_basename($this->error->exception_class) : 'Error',
                'message' => substr($this->error->message, 0, 300),
            ]))
            ->button(__('View in AMPP'), action(ShowImplementationController::class, $this->implementation));
    }
}
