<?php

namespace App\DataTables\Ampp;

use App\Models\Expense;
use App\Traits\DataTableHelpers;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseDataTable extends DataTable
{
    use DataTableHelpers;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->only($this->getAllColumnNames())
            ->addColumn('supplier_full_name', fn(Expense $expense) => $expense->supplier?->full_name)
            ->editColumn('status', fn (Expense $expense) => "<span class='badge rounded-pill bg-{$expense->status->color()}'>{$expense->status->label()}</span>")
            ->editColumn('sent_to_clearfacts_at', fn (Expense $expense) => view('ampp.expenses.columns.sentToClearfactsAt', ['expense' => $expense]))
            ->editColumn('invoice_date', fn(Expense $expense) => $expense->invoice_date?->format('d/m/Y'))
            ->editColumn('comment', fn(Expense $expense) => Str::limit(strip_tags($expense->comment), 50))
            ->editColumn('created_at', fn(Expense $expense) => $expense->created_at->format('d/m/Y H:i'))
            ->editColumn('amount_excluding_vat', fn (Expense $expense) => $expense->amount_excluding_vat_formatted)
            ->editColumn('amount_including_vat', fn (Expense $expense) => $expense->amount_including_vat_formatted)
            ->addColumn('invoice_category', fn (Expense $expense) => $expense->invoiceCategory?->name)
            ->addColumn('action', 'ampp.expenses.columns.action')
            ->rawColumns(['action', 'status', 'sent_to_clearfacts_at'])
            ->setRowAttr(['data-id' => fn(Expense $expense) => $expense->id])
        ;
    }

    public function query(Expense $model)
    {
        $query = $model
            ->newQuery()
            ->with(['supplier', 'invoiceCategory'])
            ->select('expenses.*');

        if (isset($this->attributes['status'])) {
            $query->where('status', $this->attributes['status']);
        }

        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('expense-table')
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
            Column::make('number')->title('Number'),
            Column::make('supplier_full_name', 'supplier.first_name')->title('Supplier'),
            Column::make('supplier.first_name')->hidden()->exportable(false)->printable(false),
            Column::make('supplier.last_name')->hidden()->exportable(false)->printable(false),
            Column::make('supplier.company')->title('Company'),
            Column::make('status')->title('Status'),
            Column::make('sent_to_clearfacts_at')->title(__('CF')),
            Column::make('amount_excluding_vat')->title('Amount excl. VAT'),
            Column::make('amount_including_vat')->title('Amount incl. VAT'),
            Column::make('comment')->title('Comment'),
            Column::make('invoice_category')->title(__('Category'))->orderable(false)->searchable(false),
            Column::make('invoice_date')->title('Invoice date'),
            Column::make('created_at')->title('Created at'),
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
        return 'Expense_'.date('YmdHis');
    }
}
