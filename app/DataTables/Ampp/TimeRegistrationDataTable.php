<?php

namespace App\DataTables\Ampp;

use App\Models\TimeRegistration;
use App\Traits\DataTableHelpers;
use App\Traits\TimeConversionTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TimeRegistrationDataTable extends DataTable
{
    use DataTableHelpers;
    use TimeConversionTrait;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->addColumn('client_name', fn(TimeRegistration $timeRegistration) => $timeRegistration->projectClient?->full_name)
            ->editColumn(
                'hours',
                fn(TimeRegistration $timeRegistration) => $this->secondsToHoursAndMinutes(
                    $timeRegistration->total_time_in_seconds ?? Carbon::parse($timeRegistration->start)->diffInSeconds(now(), absolute: true)
                )
            )
            ->editColumn('start', fn(TimeRegistration $timeRegistration) => $timeRegistration->start->format('l d M Y H:i'))
            ->editColumn('end', fn(TimeRegistration $timeRegistration) => $timeRegistration->end?->format('H:i'))
            ->editColumn(
                'description',
                fn(TimeRegistration $timeRegistration) => view(
                    'ampp.projects.columns.description',
                    ['description' => $timeRegistration->description]
                )
            )
            ->setRowAttr([
                'data-user-id' => fn(TimeRegistration $timeRegistration) => $timeRegistration->user_id,
                'data-date' => fn(TimeRegistration $timeRegistration) => $timeRegistration->start->format('Y-m-d'),
            ])
        ;
    }

    public function query(TimeRegistration $model)
    {
        $query = $model
            ->newQuery()
            ->with(['project', 'project.client', 'user', 'projectActivity'])
        ;

        if (isset($this->attributes['user']) && auth()->user()->can('viewOtherUsers', TimeRegistration::class)){
            $query->where('user_id', '=', $this->attributes['user']);
        } else {
            $query->where('user_id', '=', auth()->id());
        }

        if (isset($this->attributes['project']) && auth()->user()->can('view', $this->attributes['project'])){
            $query->where('project_id', '=', $this->attributes['project']);
        }

        // Temp all users can see all time registrations
        $query->where('project_id', '=', $this->attributes['project']);

        return $query->select('time_registrations.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('timeRegistration-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(3)
            ->stateSave()
            ->pageLength(15)
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        $columns = [
            Column::make('client_name', 'project.client.first_name')->title(__('Client')),
            Column::make('project.name')->title(__('Project')),
            Column::make('start')->title(__('Start')),
            Column::make('end')->title(__('End')),
            Column::make('hours', 'total_time_in_seconds')->searchable(false)->title(__('Hours')),
            Column::make('project_activity.name', 'projectActivity.name')->title(__('Activity')),
            Column::make('description')->orderable(false)->className('description-column')->title(__('Description'))->responsivePriority(1),
        ];

        if (auth()->user()->can('viewOtherUsers', TimeRegistration::class)){
            array_unshift(
                $columns,
                Column::make('user.name')->title(__('User'))
            );
        }

        return $columns;
    }

    protected function filename(): string
    {
        return 'TimeRegistration_'.date('YmdHis');
    }
}
