<?php

namespace App\DataTables\Ampp;

use App\Models\Client;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ClientDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('created_at', fn (Client $client) => $client->created_at->format('Y-m-d H:i'))
            ->addColumn('full_name', fn (Client $client) => $client->full_name)
            ->editColumn('type', fn (Client $client) => view('ampp.clients.columns.type', ['client' => $client]))
            ->addColumn('invoice_category', fn (Client $client) => $client->invoiceCategory?->name)
            ->addColumn('action', 'ampp.clients.columns.action')
            ->setRowAttr(['data-id' => fn(Client $client) => $client->id])
        ;
    }

    public function query(Client $model)
    {
        $query = $model->newQuery()->with('invoiceCategory');

        if (isset($this->attributes['archive'])) {
            $query->onlyTrashed();
        }

        if (isset($this->attributes['type'])) {
            $query->where('type', $this->attributes['type']);
        }

        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('client-table')
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
            Column::make('full_name')->title(__('Full name')),
            Column::make('first_name')->exportable(false)->printable(false)->hidden(),
            Column::make('last_name')->exportable(false)->printable(false)->hidden(),
            Column::make('type')->title(__('Type')),
            Column::make('company')->title(__('Company')),
            Column::make('email')->title(__('Email')),
            Column::make('phone')->title(__('Phone')),
            Column::make('invoice_category')->title(__('Category'))->orderable(false)->searchable(false),
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
        return 'Client_'.date('YmdHis');
    }
}
