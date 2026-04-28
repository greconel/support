<?php

namespace App\DataTables\Ampp;

use App\Models\GdprMessage;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GdprMessageDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('created_at', fn (GdprMessage $message) => $message->created_at->format('d/m/Y'))
            ->addColumn('action', 'ampp.gdprMessages.action')
            ->setRowAttr(['data-id' => fn(GdprMessage $message) => $message->id])
        ;
    }

    public function query(GdprMessage $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('gdprMessage-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Brt<"pagination"ip>l')
            ->orderBy(9)
            ->stateSave()
            ->pageLength(15)
            ->responsive()
            ->buttons([
                Button::make('excel')
            ])
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('when')->title(__('When')),
            Column::make('description')->title(__('Description')),
            Column::make('what')->title(__('What')),
            Column::make('amount_of_details')->title(__('Amount of details')),
            Column::make('category')->title(__('Category')),
            Column::make('type')->title(__('Type')),
            Column::make('consequences')->title(__('Consequences')),
            Column::make('measures')->title(__('Measures')),
            Column::make('notification_requirements')->title(__('Notification requirements')),
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
        return 'GdprMessage_'.date('YmdHis');
    }
}
