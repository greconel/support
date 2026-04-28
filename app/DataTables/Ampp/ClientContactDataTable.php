<?php

namespace App\DataTables\Ampp;

use App\Models\ClientContact;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ClientContactDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('tags', fn(ClientContact $contact) => view('ampp.clientContacts.columns.tags', ['tags' => $contact->tags]))
            ->addColumn('client.full_name', fn (ClientContact $contact) => $contact->client->full_name)
            ->addColumn('full_name', fn (ClientContact $contact) => $contact->full_name)
            ->addColumn('action', 'ampp.clientContacts.columns.action')
            ->setRowAttr(['data-id' => fn(ClientContact $contact) => $contact->id])
        ;
    }

    public function query(ClientContact $model)
    {
        return $model
            ->newQuery()
            ->with('client')
            ->whereRelation('client', 'deleted_at', '=', null)
            ->select('client_contacts.*')
        ;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('clientContact-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(0, 'asc')->orderBy(4, 'asc')
            ->stateSave()
            ->pageLength(15)
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('client.company')->title(__('Company')),
            Column::make('client.full_name', 'client.first_name')->title(__('Client')),
            Column::make('client.first_name')->hidden()->exportable(false)->printable(false),
            Column::make('client.last_name')->hidden()->exportable(false)->printable(false),
            Column::make('full_name', 'first_name')->title(__('Name')),
            Column::make('first_name')->hidden()->exportable(false)->printable(false),
            Column::make('last_name')->hidden()->exportable(false)->printable(false),
            Column::make('email')->title(__('Email')),
            Column::make('tags')->title(__('Tags')),
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
        return 'ClientContact_'.date('YmdHis');
    }
}
