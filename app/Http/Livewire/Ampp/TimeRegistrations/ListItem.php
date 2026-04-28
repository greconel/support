<?php

namespace App\Http\Livewire\Ampp\TimeRegistrations;

use App\Models\TimeRegistration;
use App\Traits\TimeConversionTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ListItem extends Component
{
    use TimeConversionTrait;

    public TimeRegistration $timeRegistration;

    public function mount(TimeRegistration $timeRegistration)
    {
        $this->timeRegistration = $timeRegistration;
    }

    public function start()
    {
        $newTimeRegistration = $this->timeRegistration->replicate()->fill([
            'start' => now(),
            'end' => null,
            'total_time_in_seconds' => null,
            'is_billable' => true,
            'is_billed' => false
        ]);

        $newTimeRegistration->save();

        $this->dispatch('refreshTimeRegistrations');
    }

    public function stop()
    {
        $this->timeRegistration->update([
            'end' => now()
        ]);

        $this->dispatch('refreshTimeRegistrations');
        $this->dispatch('editTimeRegistration', id: $this->timeRegistration->id);
    }

    public function calculateTotalTime(TimeRegistration $timeRegistration): string
    {
        $seconds = $timeRegistration->end
            ? $timeRegistration->total_time_in_seconds
            : now()->diffInSeconds($timeRegistration->start, absolute: true);

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function render(): View
    {
        return view('livewire.ampp.time-registrations.list-item');
    }
}
