<?php

namespace App\DataTables\Ampp;

use App\Models\RecurringInvoice;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RecurringInvoiceDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->addColumn('checkbox', fn (RecurringInvoice $ri) => '<input type="checkbox" class="recurring-invoice-checkbox" value="' . $ri->id . '">')
            ->editColumn('client_full_name', fn (RecurringInvoice $ri) => $ri->client->full_name)
            ->editColumn('client.company', fn (RecurringInvoice $ri) => $ri->client->company)
            ->editColumn('period', fn (RecurringInvoice $ri) => $ri->period->label())
            ->editColumn('is_active', fn (RecurringInvoice $ri) => $ri->is_active
                ? '<span class="badge rounded-pill bg-success">' . __('Active') . '</span>'
                : '<span class="badge rounded-pill bg-secondary">' . __('Inactive') . '</span>'
            )
            ->editColumn('amount_with_vat', fn (RecurringInvoice $ri) => $ri->amount_with_vat ? $ri->amount_with_vat_formatted : '-')
            ->editColumn('last_generated_at', fn (RecurringInvoice $ri) => $ri->last_generated_at?->format('d/m/Y') ?? '-')
            ->addColumn('action', 'ampp.recurring-invoices.columns.action')
            ->rawColumns(['checkbox', 'is_active', 'action'])
            ->setRowAttr(['data-id' => fn(RecurringInvoice $ri) => $ri->id])
        ;
    }

    public function query(RecurringInvoice $model)
    {
        $query = $model
            ->newQuery()
            ->with(['client'])
            ->select('recurring_invoices.*');

        if (isset($this->attributes['period'])) {
            $query->where('period', $this->attributes['period']);
        }

        if (isset($this->attributes['is_active'])) {
            $query->where('is_active', $this->attributes['is_active']);
        }

        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('recurring-invoice-table')
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
            Column::computed('checkbox')
                ->title('<input type="checkbox" id="select-all-recurring">')
                ->exportable(false)
                ->printable(false)
                ->width(30)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
            Column::make('name')->responsivePriority(1)->title(__('Name')),
            Column::make('client_full_name', 'client.first_name')->responsivePriority(1)->title(__('Client')),
            Column::make('client.company')->responsivePriority(3)->title(__('Company')),
            Column::make('period')->responsivePriority(2)->title(__('Period')),
            Column::make('is_active')->responsivePriority(2)->title(__('Status')),
            Column::make('amount_with_vat')->responsivePriority(1)->title(__('incl. VAT')),
            Column::make('last_generated_at')->responsivePriority(2)->title(__('Last used')),
            Column::computed('action')
                ->responsivePriority(0)
                ->title('')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename()
    {
        return 'RecurringInvoice_' . date('YmdHis');
    }
}
