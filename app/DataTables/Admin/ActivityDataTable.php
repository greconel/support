<?php

namespace App\DataTables\Admin;

use App\Traits\DataTableHelpers;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ActivityDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('created_at', fn(Activity $activity) => $activity->created_at->format('d/m/Y H:i'))
            ->addColumn('action', 'admin.activityLogs.action')
            ->setRowAttr(['data-id' => fn(Activity $activity) => $activity->id])
        ;
    }

    public function query(Activity $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('activity-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(5)
            ->stateSave()
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('description')->title(__('Description')),
            Column::make('subject_type')->title(__('Subject type')),
            Column::make('subject_id')->title(__('Subject ID')),
            Column::make('causer_type')->title(__('Causer type')),
            Column::make('causer_id')->title('Causer ID'),
            Column::make('created_at')->title('Created at'),
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
        return 'Activity_'.date('YmdHis');
    }
}
