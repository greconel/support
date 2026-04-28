<?php

namespace App\DataTables\Ampp;

use App\Models\HelpMessage;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HelpMessageDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('created_at', fn (HelpMessage $message) => $message->created_at->format('y-m-d H:i'))
            ->addColumn('action', 'ampp.helpMessages.action')
            ->setRowAttr(['data-id' => fn(HelpMessage $message) => $message->id])
        ;
    }

    public function query(HelpMessage $model)
    {
        return $model
            ->newQuery()
            ->where('user_id', auth()->id());
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('helpMessage-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(1)
            ->stateSave()
            ->responsive()
            ->pageLength(15)
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('title')->title(__('Title')),
            Column::make('created_at')->title(__('Created at')),
            Column::computed('action')
                ->responsivePriority(1)
                ->title('')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-end'),
        ];
    }

    protected function filename(): string
    {
        return 'HelpMessage_'.date('YmdHis');
    }
}
