<?php

namespace App\DataTables\Ampp;

use App\Models\GdprRegister;
use App\Traits\DataTableHelpers;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GdprRegisterDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->editColumn('processing_activity', fn (GdprRegister $register) => __($register->processing_activity))
            ->editColumn('processing_purpose', fn (GdprRegister $register) => __($register->processing_purpose))
            ->editColumn('subject_category', fn (GdprRegister $register) => __($register->subject_category))
            ->editColumn('data_type', fn (GdprRegister $register) => __($register->data_type))
            ->editColumn('legal_basis', fn (GdprRegister $register) => __($register->legal_basis))
            ->editColumn('nature_transfers', fn (GdprRegister $register) => __($register->nature_transfers))
            ->editColumn('created_at', fn (GdprRegister $register) => $register->created_at->format('d/m/Y'))
            ->addColumn('action', 'ampp.gdprRegisters.action')
            ->setRowAttr(['data-id' => fn(GdprRegister $register) => $register->id])
        ;
    }

    public function query(GdprRegister $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('gdprRegister-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Brt<"pagination"ip>l')
            ->orderBy(18)
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
            Column::make('processing_activity')->title(__('Processing activity')),
            Column::make('processing_activity_input')->hidden()->searchable(false)->orderable(false),
            Column::make('processing_purpose')->title(__('Processing purpose')),
            Column::make('processing_purpose_input')->hidden()->searchable(false)->orderable(false),
            Column::make('subject_category')->title(__('Subject category')),
            Column::make('subject_category_input')->hidden()->searchable(false)->orderable(false),
            Column::make('data_type')->title(__('Data type')),
            Column::make('data_type_input')->hidden()->searchable(false)->orderable(false),
            Column::make('receiver_type')->hidden()->searchable(false)->orderable(false),
            Column::make('retention_period')->hidden()->searchable(false)->orderable(false),
            Column::make('legal_basis')->hidden()->searchable(false)->orderable(false),
            Column::make('legal_basis_input')->hidden()->searchable(false)->orderable(false),
            Column::make('transfers_to')->hidden()->searchable(false)->orderable(false),
            Column::make('nature_transfers')->hidden()->searchable(false)->orderable(false),
            Column::make('nature_transfers_input')->hidden()->searchable(false)->orderable(false),
            Column::make('technical_measures')->hidden()->searchable(false)->orderable(false),
            Column::make('database')->hidden()->searchable(false)->orderable(false),
            Column::make('access')->hidden()->searchable(false)->orderable(false),
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
        return 'GdprRegister_'.date('YmdHis');
    }
}
