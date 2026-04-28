<?php

namespace App\View\Components\Ampp\ActivityLogs\Timeline;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Activitylog\Models\Activity;

class Event extends Component
{
    public Activity $activity;
    public ?User $user;
    public string $side;

    public function __construct(Activity $activity, string $side = 'left')
    {
        $this->activity = $activity;
        $this->side = $side;
        $this->user = $activity->causer ?? new User(['name' => '!! deleted !!']);
    }

    public function render(): View
    {
        return view('components.ampp.activity-logs.timeline.event');
    }
}
