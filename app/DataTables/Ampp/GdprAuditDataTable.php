<?php

namespace App\DataTables\Ampp;

use App\Models\GdprAudit;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GdprAuditDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('created_at', fn (GdprAudit $audit) => $audit->created_at->format('d/m/Y'))
            ->addColumn('action', 'ampp.gdprAudits.action')
            ->setRowAttr(['data-id' => fn(GdprAudit $audit) => $audit->id])
        ;
    }

    public function query(GdprAudit $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('gdprAudit-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Brt<"pagination"ip>l')
            ->orderBy(3)
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
            Column::make('what')->title(__('What')),
            Column::make('when')->title(__('When')),
            Column::make('why')->title(__('Why')),
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
        return 'GdprAudit_'.date('YmdHis');
    }
}
