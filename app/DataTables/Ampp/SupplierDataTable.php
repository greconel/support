<?php

namespace App\DataTables\Ampp;

use App\Models\Supplier;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('created_at', fn (Supplier $supplier) => $supplier->created_at->format('Y-m-d H:i'))
            ->addColumn('full_name', fn (Supplier $supplier) => $supplier->full_name)
            ->addColumn('invoice_category', fn (Supplier $supplier) => $supplier->invoiceCategory?->name)
            ->addColumn('action', 'ampp.suppliers.action')
            ->setRowAttr(['data-id' => fn(Supplier $supplier) => $supplier->id])
        ;
    }

    public function query(Supplier $model)
    {
        return $model->newQuery()->with('invoiceCategory');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('supplier-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(7)
            ->stateSave()
            ->pageLength(15)
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('company')->title(__('Company')),
            Column::make('full_name')->title(__('Full name')),
            Column::make('first_name')->exportable(false)->printable(false)->hidden(),
            Column::make('last_name')->exportable(false)->printable(false)->hidden(),
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
        return 'Supplier_'.date('YmdHis');
    }
}
