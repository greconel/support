<?php

namespace App\DataTables\Admin;

use App\Traits\DataTableHelpers;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->addColumn('user_count', fn(Role $role) => $role->users()->count())
            ->editColumn('created_at', fn(Role $role) => $role->created_at->format('d/m/Y H:i'))
            ->editColumn('updated_at', fn(Role $role) => $role->updated_at->format('d/m/Y H:i'))
            ->addColumn('action', 'admin.roles.action')
            ->setRowAttr(['data-id' => fn(Role $role) => $role->id])
        ;
    }

    public function query(Role $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('role-table')
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
            Column::make('user_count')
                ->searchable(false)
                ->orderable(false)
                ->title(__('Users with this role')),
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
        return 'Role_'.date('YmdHis');
    }
}
