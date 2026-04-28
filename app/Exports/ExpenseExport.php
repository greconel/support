<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpenseExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        return Expense::all();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Supplier ID'),
            __('Supplier name'),
            __('Supplier company'),
            __('Number'),
            __('Name'),
            __('Invoice date'),
            __('Invoice number'),
            __('Invoice OGM'),
            __('Status'),
            __('Is approved for payment'),
            __('Amount excl. VAT'),
            __('Amount incl. VAT'),
            __('Amount of VAT'),
            __('Comment'),
            __('Invoice category'),
            __('Created at'),
            __('Last updated at'),
        ];
    }

    /** @var Expense $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->supplier_id,
            $row->supplier->full_name,
            $row->supplier->company,
            $row->number,
            $row->name,
            $row->invoice_date?->format('d/m/Y'),
            $row->invoice_number,
            $row->invoice_ogm,
            $row->status->label(),
            $row->is_approved_for_payment ? __('Yes') : __('No'),
            $row->amount_excluding_vat,
            $row->amount_including_vat,
            $row->amount_vat,
            strip_tags($row->comment),
            $row->invoiceCategory?->name,
            $row->created_at?->format('d/m/Y H:i'),
            $row->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
