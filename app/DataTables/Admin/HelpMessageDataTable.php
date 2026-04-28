<?php

namespace App\DataTables\Admin;

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
            ->editColumn('created_at', fn(HelpMessage $message) => $message->created_at->format('y-m-d H:i'))
            ->addColumn('action', 'admin.helpMessages.action')
            ->setRowAttr(['data-id' => fn(HelpMessage $message) => $message->id])
        ;
    }

    public function query(HelpMessage $model)
    {
        return $model
            ->newQuery()
            ->with('user')
            ->select('help_messages.*')
        ;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('helpMessage-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(config('datatables-buttons.parameters.dom'))
            ->orderBy(2)
            ->stateSave()
            ->pageLength(15)
            ->responsive()
        ;
    }

    protected function getColumns()
    {
        return [
            Column::make('user.name')->title(__('User')),
            Column::make('title')->title(__('Title')),
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
        return 'HelpMessage_'.date('YmdHis');
    }
}
