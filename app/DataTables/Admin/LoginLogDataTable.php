<?php

namespace App\DataTables\Admin;

use App\Models\LoginLog;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LoginLogDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('created_at', fn (LoginLog $loginLog) => $loginLog->created_at->format('d/m/Y H:i'))
            ->editColumn('via_remember_me', function (LoginLog $loginLog) {
                return $loginLog->via_remember_me
                    ? '<span class="text-info">'.__('Yes').'</span>'
                    : '<span class="text-secondary">'.__('No').'</span>';
            })
            ->addColumn('action', 'admin.loginLogs.action')
            ->rawColumns(['action', 'via_remember_me'])
            ->setRowAttr(['data-id' => fn(LoginLog $loginLog) => $loginLog->id])
        ;
    }

    public function query(LoginLog $model)
    {
        return $model->newQuery()->with('user');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('loginLog-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(3)
            ->stateSave()
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('user.name')->title(__('User')),
            Column::make('ip_address')->title(__('IP address')),
            Column::computed('via_remember_me')->title(__('Via remember me')),
            Column::make('created_at')->title(__('Created at')),
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
        return 'LoginLog_'.date('YmdHis');
    }
}
