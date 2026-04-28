<?php

namespace App\Http\Livewire\Ampp\Implementations;

use App\Models\Implementation;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ManualScheduleManager extends Component
{
    use LivewireAlert;

    public Implementation $implementation;

    public string $command = '';
    public string $expression = '';
    public string $description = '';
    public string $timezone = 'Europe/Brussels';
    public ?int $gracePeriod = null;

    protected $rules = [
        'command' => 'required|string|max:255',
        'expression' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'timezone' => 'required|string|max:255',
        'gracePeriod' => 'nullable|integer|min:1|max:1440',
    ];

    public function addSchedule()
    {
        $this->validate();

        $this->implementation->schedules()->create([
            'command' => $this->command,
            'expression' => $this->expression,
            'description' => $this->description ?: null,
            'timezone' => $this->timezone,
            'grace_period_minutes' => $this->gracePeriod,
            'is_active' => true,
        ]);

        $this->reset(['command', 'expression', 'description', 'gracePeriod']);
        $this->alert('success', __('Schedule added'));
        $this->dispatch('$refresh')->to('ampp.implementations.schedule-list');
    }

    public function render()
    {
        return view('livewire.ampp.implementations.manual-schedule-manager');
    }
}
