<?php

namespace App\DataTables\Admin;

use App\Traits\DataTableHelpers;
use Laravel\Passport\Client;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PassportClientDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('user', fn (Client $client) => $client->user?->name)
            ->editColumn('secret', fn (Client $client) => $client->secret)
            ->editColumn('revoked_text', fn (Client $client) => $client->revoked ? '<span class="text-danger">'.__('Token is revoked').'</span>' : null)
            ->editColumn('created_at', fn (Client $client) => $client->created_at->format('d/m/Y H:i'))
            ->addColumn('action', 'admin.passportClients.action')
            ->rawColumns(['action', 'revoked_text'])
            ->setRowAttr(['data-id' => fn(Client $client) => $client->id])
        ;
    }

    public function query(Client $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('passportClient-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(0, 'asc')
            ->stateSave()
            ->pageLength(15)
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('id'),
            Column::computed('secret')->title(__('Secret')),
            Column::computed('user')->title(__('User')),
            Column::make('name')->title(__('Passport client name')),
            Column::make('revoked_text', 'revoked')->title(__('Revoked')),
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
        return 'PassportClient_'.date('YmdHis');
    }
}
