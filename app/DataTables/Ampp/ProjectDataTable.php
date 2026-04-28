<?php

namespace App\DataTables\Ampp;

use App\Models\Project;
use App\Models\User;
use App\Traits\DataTableHelpers;
use App\Traits\TimeConversionTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProjectDataTable extends DataTable
{
    use DataTableHelpers;
    use TimeConversionTrait;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('name', fn(Project $project) => view('ampp.projects.columns.name', ['project' => $project]))
            ->addColumn(
                'category',
                fn(Project $project) => auth()->user()->can('update', $project)
                    ? view('ampp.projects.columns.category', compact('project'))
                    : $project->category->value
            )
            ->addColumn(
                'description',
                fn(Project $project) => auth()->user()->can('update', $project)
                    ? view('ampp.projects.columns.projectDescription', compact('project'))
                    : Str::limit(strip_tags($project->description), 50)
            )
            ->addColumn(
                'assignee',
                fn(Project $project) => auth()->user()->can('update', $project)
                    ? view('ampp.projects.columns.assignee', compact('project'))
                    : $this->getUserName($project->assignee)
            )
            ->addColumn('client_full_name', fn(Project $project) => $project->client?->full_name)
            ->addColumn('deadline',
                fn(Project $project) => auth()->user()->can('update', $project)
                    ? view('ampp.projects.columns.deadline', compact('project'))
                    : ($project->deadline ? Carbon::parse($project->deadline)->format('d/m/Y') : '')
            )
            ->addColumn('latest_activity', function (Project $project) {
                $latestActivity = $project->timeRegistrations()->orderByDesc('start')->first();

                if ($latestActivity){
                    return Carbon::parse($latestActivity->start)->diffForHumans();
                }

                return null;
            })
            ->editColumn('members', fn(Project $project) => view('ampp.projects.columns.members', ['users' => $project->users]))
            ->addColumn('action', 'ampp.projects.columns.action')
            ->setRowAttr(['data-id' => fn(Project $project) => $project->id])
            ->rawColumns(['category', 'description', 'action'])
        ;
    }

    public function query(Project $model)
    {
        $query = $model
            ->newQuery()
            ->with(['client', 'users', 'timeRegistrations'])
            ->select('projects.*');

        if($assignee = $this->attributes['assignee']) {
            $query->where('assignee', '=', $assignee);
        }

        if ($category = $this->attributes['category']) {
            $query->where('category', '=', $category);
        }

        if ($this->attributes['archive']) {
            $query->onlyTrashed();
        }

        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('project-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(1, 'asc')
            ->orderBy(0, 'asc')
            ->stateSave()
            ->pageLength(15)
            ->responsive()
            ->drawCallbackWithLivewire()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('name')->title(__('Name')),
            Column::make('client_full_name', 'client.first_name')->title(__('Client')),
            Column::make('client.first_name')->hidden(),
            Column::make('client.last_name')->hidden(),
            Column::make('client.company')->title(__('Company')),
            Column::make('category')->title(__('Category')),
            Column::make('assignee')->title(__('Assignee')),
            Column::make('description')->title(__('Description'))->searchable(false)->orderable(false),
            Column::make('deadline')->title(__('Deadline')),
            Column::computed('latest_activity')->title(__('Last active')),
            Column::make('members', 'users.name')->className('w-1/5')->orderable(false)->title(__('Members')),
            Column::computed('action')
                ->responsivePriority(1)
                ->title('')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Project_'.date('YmdHis');
    }

    private function getUserName($userId) {
        if($userId == null || $userId == '') return '';
        if($userId == 'team') return 'Team';
        return User::find($userId)?->name;
    }
}
