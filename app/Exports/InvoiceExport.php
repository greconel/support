<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoiceExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        return Invoice::all();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Client ID'),
            __('Client name'),
            __('Client company'),
            __('Number'),
            __('Type'),
            __('OGM'),
            __('Status'),
            __('Amount'),
            __('Amount incl. VAT'),
            __('Notes'),
            __('Comment for PDF'),
            __('Expiration date'),
            __('Created at'),
            __('Paid at'),
            __('Invoice category'),
            __('Last updated at'),
        ];
    }

    /** @var Invoice $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->client_id,
            $row->client->full_name,
            $row->client->company,
            $row->number,
            $row->type->label(),
            $row->ogm,
            $row->status->label(),
            $row->amount,
            $row->amount_with_vat,
            strip_tags($row->notes),
            strip_tags($row->pdf_comment),
            $row->expiration_date?->format('d/m/Y'),
            $row->custom_created_at?->format('d/m/Y'),
            $row->paid_at?->format('d/m/Y'),
            $row->invoiceCategory?->name,
            $row->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
