<?php

namespace App\DataTables\Ampp;

use App\Models\QrCode;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class QrCodeDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->addColumn('image', function (QrCode $qrCode) {
                if (! $qrCode->getFirstMedia('image')) {
                    return null;
                }

                $url = action(\App\Http\Controllers\Media\ShowMediaController::class, $qrCode->getFirstMedia('image'));

                return "<img src='{$url}' alt='image' class='img-fluid d-block mx-auto' width='100' height='100' />";
            })
            ->editColumn('created_at', fn (QrCode $qrCode) => $qrCode->created_at->format('d/m/Y H:i'))
            ->addColumn('action', 'ampp.qrCodes.action')
            ->rawColumns(['action', 'image'])
        ;
    }

    public function query(QrCode $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('qrCode-table')
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
            Column::make('name')->title(__('Name')),
            Column::make('notes')->title(__('Notes')),
            Column::computed('image')->title(__('Image')),
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
        return 'QrCode_'.date('YmdHis');
    }
}
