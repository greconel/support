<?php

namespace App\Http\Livewire\Ampp\Implementations;

use App\Models\Implementation;
use App\Models\ImplementationSchedule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ScheduleList extends Component
{
    use LivewireAlert;

    public Implementation $implementation;

    protected $listeners = ['refreshScheduleList' => '$refresh'];

    public ?int $editingId = null;
    public ?int $editGracePeriod = null;
    public string $editDescription = '';

    public function edit(int $scheduleId)
    {
        $schedule = ImplementationSchedule::findOrFail($scheduleId);
        $this->editingId = $scheduleId;
        $this->editGracePeriod = $schedule->grace_period_minutes;
        $this->editDescription = $schedule->description ?? '';
    }

    public function save()
    {
        $this->validate([
            'editGracePeriod' => 'nullable|integer|min:1|max:1440',
            'editDescription' => 'nullable|string|max:255',
        ]);

        $schedule = ImplementationSchedule::findOrFail($this->editingId);
        $schedule->update([
            'grace_period_minutes' => $this->editGracePeriod,
            'description' => $this->editDescription ?: null,
        ]);

        $this->cancelEdit();
        $this->alert('success', __('Schedule updated'));
    }

    public function cancelEdit()
    {
        $this->editingId = null;
        $this->editGracePeriod = null;
        $this->editDescription = '';
    }

    public function toggleActive(int $scheduleId)
    {
        $schedule = ImplementationSchedule::findOrFail($scheduleId);
        $schedule->update(['is_active' => !$schedule->is_active]);
    }

    public function deleteSchedule(int $scheduleId)
    {
        ImplementationSchedule::findOrFail($scheduleId)->delete();
        $this->alert('success', __('Schedule removed'));
    }

    public function render()
    {
        $schedules = $this->implementation->schedules()
            ->with('latestExecution')
            ->get();

        return view('livewire.ampp.implementations.schedule-list', compact('schedules'));
    }
}
