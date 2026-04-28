<?php

namespace App\DataTables\Admin;

use App\Traits\DataTableHelpers;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PermissionDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('created_at', fn(Permission $permission) => $permission->created_at->format('d/m/Y H:i'))
            ->editColumn('updated_at', fn(Permission $permission) => $permission->updated_at->format('d/m/Y H:i'))
            ->addColumn('action', 'admin.permissions.action')
            ->setRowAttr(['data-id' => fn(Permission $permission) => $permission->id])
        ;
    }

    public function query(Permission $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('permission-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(0)
            ->stateSave()
            ->pageLength(15)
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('name')->title(__('Name')),
            Column::make('created_at')->title(__('Created at')),
            Column::make('updated_at')->title(__('Last updated at')),
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
        return 'Permission_'.date('YmdHis');
    }
}
