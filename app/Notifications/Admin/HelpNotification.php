<?php

namespace App\Notifications\Admin;

use App\Models\HelpMessage;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class HelpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public HelpMessage $message;

    public function __construct(HelpMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [MicrosoftTeamsChannel::class];
    }

    public function toMicrosoftTeams($notifiable)
    {
        $content = <<<HTML
        {$this->message->title}

        {$this->message->message}
        HTML;

        if ($this->message->hasMedia('images')){
            $content .= '<br><br><br>';

            foreach ($this->message->getMedia('images') as $media){
                $url = action(\App\Http\Controllers\Media\ShowMediaController::class, $media);
                $content .= "<a href='{$url}'>{$url}</a> <br />";
            }
        }


        return MicrosoftTeamsMessage::create()
            ->to(config('services.teams.webhook_url'))
            ->type('warning')
            ->title('New help message from: ' . config('app.name') . ' - ' . $this->message->user->name)
            ->content($content)
            ->button('Open AMPP', route('login'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
