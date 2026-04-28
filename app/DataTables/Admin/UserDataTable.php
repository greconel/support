<?php

namespace App\DataTables\Admin;

use App\Models\User;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->addColumn('role_names', fn(User $user) => view('admin.users.columns.roles', ['roles' => $user->roles]))
            ->editColumn('last_active_at', fn(User $user) => view('admin.users.columns.lastActiveAt', ['user' => $user]))
            ->editColumn('created_at', fn(User $user) => $user->created_at->format('d/m/Y H:i'))
            ->editColumn('updated_at', fn(User $user) => $user->updated_at->format('d/m/Y H:i'))
            ->addColumn('action', 'admin.users.columns.action')
            ->setRowAttr(['data-id' => fn(User $user) => $user->id])
        ;
    }

    public function query(User $model)
    {
        $query = $model
            ->newQuery()
            ->with('roles')
            ->where(function ($query){
                $query
                    ->whereRelation('roles', 'name', '!=', 'super admin')
                    ->orWhereDoesntHave('roles')
                ;
            })
        ;

        if ($this->attributes['role']) {
            $query->whereRelation('roles', 'name', '=', $this->attributes['role']);
        }

        if ($this->attributes['archive']){
            $query->onlyTrashed();
        }

        return $query->select('users.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(0)
            ->pageLength(15)
            ->stateSave()
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('name')->title(__('Name')),
            Column::make('email')->title(__('Email')),
            Column::make('role_names', 'roles.name')->orderable(false)->title(__('Roles')),
            Column::make('last_active_at')->title(__('Last active')),
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
        return 'User_'.date('YmdHis');
    }
}
