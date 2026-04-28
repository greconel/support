<?php

namespace App\DataTables\Ampp;

use App\Models\TimeRegistration;
use App\Traits\DataTableHelpers;
use App\Traits\TimeConversionTrait;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DetailedTimeReportDataTable extends DataTable
{
    use DataTableHelpers;
    use TimeConversionTrait;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->addColumn('client_full_name', fn(TimeRegistration $timeRegistration) => $timeRegistration->projectClient?->full_name_with_company)
            ->editColumn('hours', fn(TimeRegistration $timeRegistration) => $this->secondsToHoursAndMinutes($timeRegistration->total_time_in_seconds))
            ->editColumn('start', fn(TimeRegistration $timeRegistration) => $timeRegistration->start?->format('l d M Y H:i'))
            ->editColumn('end', fn(TimeRegistration $timeRegistration) => $timeRegistration->end?->format('l d M Y H:i'))
            ->editColumn('is_billable', fn(TimeRegistration $timeRegistration) => $timeRegistration->is_billable ? __('Yes') : __('No'))
            ->editColumn('is_billed', fn(TimeRegistration $timeRegistration) => $timeRegistration->is_billed ? __('Yes') : __('No'))
            ->editColumn(
                'description',
                fn(TimeRegistration $timeRegistration) => view(
                    'ampp.projectReports.columns.description',
                    ['description' => $timeRegistration->description]
                )
            )
            ->addColumn('actions', fn(TimeRegistration $timeRegistration) => view(
                'ampp.projectReports.columns.actions',
                ['timeRegistration' => $timeRegistration]
            ))
            ->addColumn('checkbox', fn(TimeRegistration $timeRegistration) => '<input type="checkbox" class="form-check-input tr-checkbox" value="' . $timeRegistration->id . '">')
            ->rawColumns(['checkbox', 'actions'])
            ->setRowAttr([
                'data-user-id' => fn(TimeRegistration $timeRegistration) => $timeRegistration->user_id,
                'data-date' => fn(TimeRegistration $timeRegistration) => $timeRegistration->start->format('Y-m-d'),
                'data-id' => fn(TimeRegistration $timeRegistration) => $timeRegistration->id,
            ])
        ;
    }

    public function query(TimeRegistration $model)
    {
        $query = $model
            ->newQuery()
            ->with(['user', 'project', 'projectClient', 'projectActivity'])
            ->whereBetween('start', [
                Carbon::parse($this->attributes['from'])->startOfDay(),
                Carbon::parse($this->attributes['till'])->endOfDay()
            ])
        ;

        if ($this->attributes['type'] == 'project'){
            $query->where('project_id', $this->attributes['id']);
        }

        if ($this->attributes['type'] == 'client'){
            $query->whereRelation('project', 'client_id', '=', $this->attributes['id']);
        }

        return $query->select('time_registrations.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('detailedTimeReport-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(3)
            ->pageLength(15)
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('checkbox')->title('<input type="checkbox" class="form-check-input" id="select-all-tr">')->searchable(false)->orderable(false)->className('text-center')->width(30),
            Column::make('client_full_name')->searchable(false)->orderable(false)->title(__('Client')),
            Column::make('project.name')->title(__('Project')),
            Column::make('user.name')->title(__('User')),
            Column::make('start')->title(__('Start')),
            Column::make('end')->title(__('End')),
            Column::make('hours', 'total_time_in_seconds')->searchable(false)->title(__('Hours')),
            Column::make('is_billable')->title(__('Billable')),
            Column::make('is_billed')->title(__('Processed')),
            Column::make('project_activity.name', 'projectActivity.name')->title(__('Activity')),
            Column::make('description')->orderable(false)->className('description-column')->title(__('Description'))->responsivePriority(1),
            Column::make('actions')->searchable(false)->orderable(false)->title('')->width(50),
        ];
    }

    protected function filename(): string
    {
        return 'DetailedTimeReport_'.date('YmdHis');
    }
}
