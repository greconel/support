<?php

namespace App\Http\Livewire\Ampp\TimeRegistrations;

use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\TimeRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class EditModal extends Component
{
    use AuthorizesRequests;
    use LivewireAlert;

    public ?TimeRegistration $timeRegistration = null;
    public Collection $projects;
    public string $start = '';
    public string $end = '';
    public string $date = '';
    public string $projectId = '';
    public string $projectActivityId = '';
    public ?string $description = null;

    protected $rules = [
        'start' => ['required', 'date_format:H:i'],
        'end' => ['nullable', 'date_format:H:i'],
        'date' => ['required', 'date_format:Y-m-d'],
        'projectId' => ['nullable', 'int'],
        'description' => ['nullable', 'string'],
    ];

    public function mount()
    {
        $this->projects = auth()
            ->user()
            ->projects
            ->pluck('name_with_client', 'id')
            ->prepend(__('-- No project --'), null)
        ;
    }

    public function updatedProjectId()
    {
        $this->reset('projectActivityId');
    }

    #[On('editTimeRegistration')]
    public function editTimeRegistration(int $id)
    {
        $this->timeRegistration = TimeRegistration::find($id);

        $this->authorize('update', $this->timeRegistration);

        $this->start = $this->timeRegistration->start?->format('H:i');
        $this->end = $this->timeRegistration->end?->format('H:i') ?? '';
        $this->date = $this->timeRegistration->start->format('Y-m-d');
        $this->projectId = $this->timeRegistration->project_id ?? '';
        $this->projectActivityId = $this->timeRegistration->project_activity_id ?? '';
        $this->description = $this->timeRegistration->description;

        $this->resetErrorBag();

        $this->dispatch('show')->self();
        $this->dispatch('refreshQuill')->self();
    }

    public function update()
    {
        $this->authorize('update', $this->timeRegistration);

        $this->validate();

        $this->validate([
            'projectId' => [Rule::in(array_keys($this->projects->toArray()))],
            'projectActivityId' => [Rule::in(ProjectActivity::where('project_id', '=', $this->projectId)->pluck('id'))],
        ]);

        $start = Carbon::createFromFormat('H:i', $this->start);
        $this->timeRegistration->start = $this->timeRegistration->start->setTimeFrom($start);

        $end = $this->end ? Carbon::createFromFormat('H:i', $this->end) : null;
        $this->timeRegistration->end ??= $this->timeRegistration->start;
        $this->timeRegistration->end = $end ? $this->timeRegistration->end->setTimeFrom($end) : null;

        if ($this->timeRegistration->start->format('Y-m-d') != $this->date){
            $date = Carbon::createFromFormat('Y-m-d', $this->date);
            $this->timeRegistration->start = $this->timeRegistration->start->setDateFrom($date);
        }

        if ($this->timeRegistration->end && $this->timeRegistration->end->format('Y-m-d') != $this->date){
            $date = Carbon::createFromFormat('Y-m-d', $this->date);
            $this->timeRegistration->end = $this->timeRegistration->end->setDateFrom($date);
        }

        $this->timeRegistration->description = $this->description;
        $this->timeRegistration->project_id = empty($this->projectId) ? null : $this->projectId;
        $this->timeRegistration->project_activity_id = empty($this->projectActivityId) ? null : $this->projectActivityId;

        $this->timeRegistration->save();
        $this->timeRegistration = null;

        $this->dispatch('hide')->self();
        $this->dispatch('refreshProjects')->self();
        $this->dispatch('refreshTimeRegistrations');

        $this->alert('success', __('Time registration updated'), ['position' => 'top']);
    }

    public function delete()
    {
        $this->authorize('delete', $this->timeRegistration);

        $this->timeRegistration->delete();
        $this->timeRegistration = null;

        $this->dispatch('hide')->self();
        $this->dispatch('refreshTimeRegistrations');
    }

    public function getProjectActivitiesProperty(): Collection
    {
        /** @var Project $project */
        $project = Project::find($this->projectId);

        if (! $project){
            return collect();
        }

        $query = $project
            ->projectActivities()
            ->where('is_active', '=', true);

        if ($this->timeRegistration?->project_activity_id) {
            $query->orWhere('id', '=', $this->timeRegistration->project_activity_id);
        }

        return $query->orderBy('name')->get();
    }

    public function render(): View
    {
        return view('livewire.ampp.time-registrations.edit-modal');
    }
}
