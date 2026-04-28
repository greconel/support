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

class CreateModal extends Component
{
    use AuthorizesRequests;
    use LivewireAlert;

    public ?int $userId = null;
    public Collection $projects;
    public string $start = '';
    public string $end = '';
    public string $date = '';
    public string $projectId = '';
    public string $projectActivityId = '';
    public ?string $description = null;

    protected $rules = [
        'start' => ['required', 'date_format:H:i'],
        'end' => ['nullable', 'date_format:H:i', 'after:start'],
        'date' => ['required', 'date_format:Y-m-d'],
        'description' => ['nullable', 'string'],
        'projectId' => ['nullable', 'int'],
        'projectActivityId' => ['nullable', 'int'],
    ];

    public function mount()
    {
        $this->projects = auth()
            ->user()
            ->projectsWithoutArchive
            ->pluck('name_with_client', 'id')
            ->prepend(__('-- No project --'), null)
        ;

    }

    public function updatedProjectId()
    {
        $this->reset('projectActivityId');
    }

    #[On('createTimeRegistration')]
    public function createTimeRegistration(string $date = null, int $userId = null, string $projectId = '')
    {
        $this->authorize('create', TimeRegistration::class);

        if (auth()->user()->can('viewOtherUsers', TimeRegistration::class)){
            $this->userId = $userId ?? auth()->id();
        } else{
            $this->userId = auth()->id();
        }

        $this->date = $date ?? null;

        if ($this->date) {
            $latestTimeRegistration = TimeRegistration::where('user_id', '=', $this->userId)
                ->whereDate('start', '=', $this->date)
                ->whereNotNull('end')
                ->orderByDesc('end')
                ->first();

            $this->start = $latestTimeRegistration ? $latestTimeRegistration->end->format('H:i') : '';
        }

        $this->projectId = $projectId;

        $this->dispatch('show')->self();
        $this->dispatch('refreshQuill')->self();
    }

    public function store()
    {
        $this->authorize('create', TimeRegistration::class);

        $this->validate();

        $this->validate([
            'projectId' => [Rule::in(array_keys($this->projects->toArray()))],
            'projectActivityId' => [Rule::in(ProjectActivity::where('project_id', '=', $this->projectId)->pluck('id'))],
        ]);

        $date = Carbon::parse($this->date);

        $timeRegistration = new TimeRegistration();

        $start = Carbon::createFromFormat('H:i', $this->start);
        $timeRegistration->start = $date->copy()->setTimeFrom($start);

        $end = $this->end ? Carbon::createFromFormat('H:i', $this->end) : null;
        $timeRegistration->end = $end ? $date->copy()->setTimeFrom($end) : null;

        $timeRegistration->description = $this->description;
        $timeRegistration->project_id = empty($this->projectId) ? null : $this->projectId;
        $timeRegistration->project_activity_id = empty($this->projectActivityId) ? null : $this->projectActivityId;
        $timeRegistration->user_id = $this->userId;

        $timeRegistration->save();

        $this->dispatch('hide')->self();
        $this->dispatch('refreshQuill')->self();
        $this->dispatch('refreshProjects')->self();
        $this->dispatch('refreshTimeRegistrations');

        $this->resetExcept(['projects']);
    }

    public function getProjectActivitiesProperty(): Collection
    {
        /** @var Project $project */
        $project = Project::find($this->projectId);

        if (! $project){
            return collect();
        }

        return $project
            ->projectActivities()
            ->where('is_active', '=', true)
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.ampp.time-registrations.create-modal');
    }
}
