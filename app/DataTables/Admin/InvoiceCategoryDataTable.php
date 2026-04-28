<?php

namespace App\DataTables\Admin;

use App\Models\InvoiceCategory;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InvoiceCategoryDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('created_at', fn(InvoiceCategory $category) => $category->created_at->format('d/m/Y H:i'))
            ->editColumn('updated_at', fn(InvoiceCategory $category) => $category->updated_at->format('d/m/Y H:i'))
            ->addColumn('action', 'ampp.invoice-categories.action')
            ->setRowAttr(['data-id' => fn(InvoiceCategory $category) => $category->id])
        ;
    }

    public function query(InvoiceCategory $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('invoice-category-table')
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

    protected function filename()
    {
        return 'InvoiceCategory_'.date('YmdHis');
    }
}
