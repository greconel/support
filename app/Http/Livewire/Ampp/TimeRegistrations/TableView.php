<?php

namespace App\Http\Livewire\Ampp\TimeRegistrations;

use App\Models\Project;
use App\Models\TimeRegistration;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TableView extends Component
{
    public ?string $userId = null;
    public ?string $projectId = null;
    public array $users = [];
    public array $projects = [];

    public function mount()
    {
        $this->userId = $this->userId ?? auth()->id();

        if (auth()->user()->can('viewOtherUsers', TimeRegistration::class)){
            $this->users = User::whereRelation('roles', 'name', '!=', 'super admin')
                ->pluck('name', 'id')
                ->toArray();
        }
        if (auth()->user()->hasRole('super admin')){
            $this->users = User::all()
                ->pluck('name', 'id')
                ->toArray();
        }

        $this->resetProjectsSelect();
    }

    public function queryString(): array
    {
        return [
            'userId' => ['as' => 'user', 'except' => strval(auth()->id())],
            'projectId' => ['as' => 'project', 'except' => null]
        ];
    }

    public function updatedUserId()
    {
        $this->refreshTable();
        $this->resetProjectsSelect();
    }

    public function updatedProjectId()
    {
        $this->refreshTable();
    }

    private function refreshTable()
    {
        $this->dispatch('refreshTable', userId: $this->userId, projectId: $this->projectId);
    }

    private function resetProjectsSelect()
    {
        $this->projects = User::find($this->userId)
            ->projects()
            ->orderBy('name')
            ->get()
            ->map(function (Project $project){
                return [
                    'name' => $project->name,
                    'value' => $project->id
                ];
            })
            ->prepend(['name' => __('All projects'), 'value' => ''])
            ->toArray()
        ;
    }

    public function render(): View
    {
        if (User::find($this->userId)->hasRole('super admin')){
            abort(403);
        }
        if (auth()->user()->cannot('viewOtherUsers', TimeRegistration::class) && $this->userId != auth()->id()){
            abort(403);
        }

        return view('livewire.ampp.time-registrations.table-view');
    }
}
