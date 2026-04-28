<?php

namespace App\DataTables\Ampp;

use App\Models\Deal;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DealDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('expected_start_date', fn(Deal $deal) => $deal->expected_start_date?->format('d/m/Y'))
            ->editColumn('due_date', function (Deal $deal) {
                if ($deal->due_date){
                    return "{$deal->due_date?->format('d/m/Y H:i')} ({$deal->due_date?->diffForHumans()})";
                }

                return null;
            })
            ->editColumn('created_at', fn(Deal $deal) => $deal->created_at?->format('d/m/Y H:i'))
            ->addColumn('action', 'ampp.deals.columns.action')
            ->setRowAttr(['data-id' => fn(Deal $deal) => $deal->id])
        ;
    }

    public function query(Deal $model)
    {
        return $model
            ->with(['dealColumn'])
            ->newQuery()
            ->select('deals.*')
        ;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('deal-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt<"pagination"ip>l')
            ->orderBy(6)
            ->pageLength(25)
            ->stateSave()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('name')->title(__('Name')),
            Column::make('deal_column.name', 'dealColumn.name')->title(__('Column')),
            Column::make('chance_of_success')->title(__('Chance of success')),
            Column::make('expected_revenue')->title(__('Expected revenue')),
            Column::make('expected_start_date')->title(__('Expected start date')),
            Column::make('due_date')->title('Due date'),
            Column::make('created_at')->title('Created at'),
            Column::computed('action')
                ->title('')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Deal_'.date('YmdHis');
    }
}
