<?php

namespace App\DataTables\Ampp;

use App\Models\TimeRegistration;
use App\Traits\DataTableHelpers;
use App\Traits\TimeConversionTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProjectTimeRegistrationDataTable extends DataTable
{
    use DataTableHelpers;
    use TimeConversionTrait;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn(
                'hours',
                fn(TimeRegistration $timeRegistration) => $this->secondsToHoursAndMinutes(
                    $timeRegistration->total_time_in_seconds ?? Carbon::parse($timeRegistration->start)->diffInSeconds(now(), absolute: true)
                )
            )
            ->editColumn('start', fn(TimeRegistration $timeRegistration) => $timeRegistration->start?->format('l d M Y H:i'))
            ->editColumn('end', fn(TimeRegistration $timeRegistration) => $timeRegistration->end?->format('H:i'))
            ->editColumn('is_billable', fn(TimeRegistration $timeRegistration) => $timeRegistration->is_billable ? __('Yes') : __('No'))
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
            ->with(['user', 'projectActivity'])
            ->where('time_registrations.project_id', '=', $this->attributes['project_id'])
        ;

        if (auth()->user()->can('view other users time registrations') && isset($this->attributes['users'])) {
            $query->whereIn('user_id', $this->attributes['users']);
        } else {
            $query->where('user_id', '=', auth()->id());
        }

        return $query->select('time_registrations.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('projectTimeRegistration-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(1)
            ->stateSave()
            ->pageLength(15)
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('user.name')->title(__('User')),
            Column::make('start')->title(__('Start')),
            Column::make('end')->title(__('End')),
            Column::make('hours', 'total_time_in_seconds')->searchable(false)->title(__('Hours')),
            Column::make('is_billable')->title(__('Is billable')),
            Column::make('project_activity.name', 'projectActivity.name')->title(__('Activity')),
            Column::make('description')->orderable(false)->className('description-column')->title(__('Description'))->responsivePriority(1),
        ];
    }

    protected function filename(): string
    {
        return 'TimeRegistration_'.date('YmdHis');
    }
}
