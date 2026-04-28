<?php

namespace App\DataTables\Ampp;

use App\Models\Quotation;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class QuotationDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('client_full_name', fn (Quotation $quotation) => $quotation->client->full_name)
            ->editColumn('notes', function (Quotation $quotation) { 
                if ($quotation->notes) {
                return strip_tags($quotation->notes);
                } else {
                    $title = "";
                    if ($quotation->billingLines->first()) {
                        $title = $quotation->billingLines->first()->text;
                    }
                    return $title;
                }
            })
            ->editColumn('created_at', fn (Quotation $quotation) => $quotation->created_at->format('d/m/Y H:i'))
            ->editColumn('status', fn (Quotation $quotation) => "<span class='badge rounded-pill bg-{$quotation->status->color()}'>{$quotation->status->label()}</span>")
            ->editColumn('amount', fn (Quotation $quotation) => $quotation->amount_formatted)
            ->editColumn('amount_with_vat', fn (Quotation $quotation) => $quotation->amount_with_vat_formatted)
            ->addColumn('action', 'ampp.quotations.action')
            ->rawColumns(['status', 'action'])
            ->setRowAttr(['data-id' => fn(Quotation $quotation) => $quotation->id])
        ;
    }

    public function query(Quotation $model)
    {
        $query = $model
            ->newQuery()
            ->with('client')
            ->select('quotations.*');

        if (isset($this->attributes['status'])) {
            $query->where('status', $this->attributes['status']);
        }

        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('quotation-table')
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
            Column::make('number')->title(__('Number')),
            Column::make('client_full_name', 'client.first_name')->title(__('Client')),
            Column::make('client.last_name')->hidden(),
            Column::make('client.company')->title(__('Company')),
            Column::make('notes')->title(__('Title')),
            Column::make('status')->title(__('Status')),
            Column::make('amount')->title(__('Amount')),
            Column::make('amount_with_vat')->title(__('Amount with VAT')),
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
        return 'Quotation_'.date('YmdHis');
    }
}
