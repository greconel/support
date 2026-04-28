<?php

namespace App\DataTables\Ampp;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('client_full_name', fn (Invoice $invoice) => $invoice->client->full_name)
            ->editColumn('custom_created_at', fn (Invoice $invoice) => $invoice->custom_created_at->format('d/m/Y'))
            ->editColumn('status', fn (Invoice $invoice) => "<span class='badge rounded-pill bg-{$invoice->status->color()}'>{$invoice->status->label()}</span>")
            ->editColumn('sent_to_recommand_at', fn (Invoice $invoice) => view('ampp.invoices.columns.sentToRecommandAt', ['invoice' => $invoice]))
            ->editColumn('sent_to_clearfacts_at', fn (Invoice $invoice) => view('ampp.invoices.columns.sentToClearfactsAt', ['invoice' => $invoice]))
            ->editColumn('amount', fn (Invoice $invoice) => view('ampp.invoices.columns.amount', ['invoice' => $invoice]))
            ->editColumn('amount_with_vat', fn (Invoice $invoice) => view('ampp.invoices.columns.amountWithVat', ['invoice' => $invoice]))
            ->editColumn('type', fn (Invoice $invoice) => $invoice->type->label())
            ->addColumn('invoice_category', fn (Invoice $invoice) => $invoice->invoiceCategory?->name)
            ->addColumn('action', 'ampp.invoices.columns.action')
            ->rawColumns(['status', 'sent_to_clearfacts_at', 'action'])
            ->setRowAttr(['data-id' => fn(Invoice $invoice) => $invoice->id])
            ->setRowClass(function (Invoice $invoice) {
                if ($invoice->status == InvoiceStatus::Paid){
                    return null;
                }

                return $invoice->expiration_date->endOfDay()->isPast() ? 'bg-red-50' : '';
            })
        ;
    }

    public function query(Invoice $model)
    {
        $query = $model
            ->newQuery()
            ->with(['client', 'payments', 'invoiceCategory'])
            ->select('invoices.*');

        if (isset($this->attributes['type'])) {
            $query->where('type', $this->attributes['type']);
        }

        if (isset($this->attributes['status'])) {
            $query->where('status', $this->attributes['status']);
        }

        if (isset($this->attributes['invoiceids'])) {
            $invoiceIds = is_string($this->attributes['invoiceids'])
                ? explode(',', $this->attributes['invoiceids'])
                : $this->attributes['invoiceids'];
            $query->whereIn('id', $invoiceIds);
        }

        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('invoice-table')
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
            Column::make('number')->responsivePriority(1)->title(__('Nr')),
            Column::make('client_full_name', 'client.first_name')->responsivePriority(1)->title(__('Client')),
            Column::make('client.last_name')->hidden(),
            Column::make('client.company')->responsivePriority(3)->title(__('Company')),
            Column::make('custom_created_at')->title(__('Created at')),
            Column::make('type')->responsivePriority(3)->title(__('Type')),
            Column::make('ogm')->responsivePriority(2)->title(__('Structured message')),
            Column::make('status')->responsivePriority(2)->title(__('Status')),
            Column::make('sent_to_recommand_at')->title(__('Ppl')),
            Column::make('sent_to_clearfacts_at')->title(__('CF')),
            Column::make('amount')->title(__('Amount')),
            Column::make('amount_with_vat')->responsivePriority(1)->title(__('incl. VAT')),
            Column::make('invoice_category')->title(__('Category'))->orderable(false)->searchable(false),
            Column::computed('action')
                ->responsivePriority(0)
                ->title('')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Invoice_'.date('YmdHis');
    }
}
