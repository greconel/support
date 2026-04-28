<?php

namespace App\Http\Livewire\Ampp\ProjectReports;

use App\Models\ProjectActivity;
use App\Models\TimeRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class EditTimeRegistrationModal extends Component
{
    use AuthorizesRequests;
    use LivewireAlert;

    public ?int $timeRegistrationId = null;
    public ?string $description = '';
    public bool $isBillable = true;
    public string $projectActivityId = '';

    public ?string $projectName = null;
    public ?int $projectId = null;

    #[On('editTimeRegistration')]
    public function editTimeRegistration(int $id)
    {
        $timeRegistration = TimeRegistration::with(['project', 'projectActivity'])->find($id);

        if (! $timeRegistration) {
            return;
        }

        $this->timeRegistrationId = $timeRegistration->id;
        $this->description = (string) $timeRegistration->description;
        $this->isBillable = (bool) $timeRegistration->is_billable;
        $this->projectActivityId = (string) ($timeRegistration->project_activity_id ?? '');
        $this->projectId = $timeRegistration->project_id;
        $this->projectName = $timeRegistration->project?->name;

        $this->resetErrorBag();

        $this->dispatch('show')->self();
        $this->dispatch('refreshQuill')->self();
    }

    public function update()
    {
        $timeRegistration = TimeRegistration::findOrFail($this->timeRegistrationId);

        $this->validate([
            'description' => ['nullable', 'string'],
            'isBillable' => ['boolean'],
            'projectActivityId' => ['nullable'],
        ]);

        $timeRegistration->description = $this->description;
        $timeRegistration->is_billable = $this->isBillable;
        $timeRegistration->project_activity_id = empty($this->projectActivityId) ? null : $this->projectActivityId;
        $timeRegistration->save();

        $this->dispatch('hide')->self();
        $this->dispatch('timeRegistrationUpdated');

        $this->alert('success', __('Time registration updated'), ['position' => 'top']);
    }

    public function getProjectActivitiesProperty(): Collection
    {
        if (! $this->projectId) {
            return collect();
        }

        return ProjectActivity::where('project_id', '=', $this->projectId)
            ->where(function ($query) {
                $query->where('is_active', '=', true)
                    ->orWhere('id', '=', $this->projectActivityId);
            })
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.ampp.project-reports.edit-time-registration-modal');
    }
}
