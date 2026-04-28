<?php

namespace App\Exports;

use App\Models\Quotation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuotationExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        return Quotation::orderByDesc('number')->get();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Client ID'),
            __('Client name'),
            __('Client company'),
            __('Number'),
            __('Status'),
            __('Amount'),
            __('Amount incl. VAT'),
            __('Notes'),
            __('Comment for PDF'),
            __('Expiration date'),
            __('Created at'),
            __('Accepted at'),
            __('Last updated at'),
        ];
    }

    /** @var Quotation $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->client_id,
            $row->client->full_name,
            $row->client->company,
            $row->number,
            $row->status->label(),
            $row->amount,
            $row->amount_with_vat,
            strip_tags($row->notes),
            strip_tags($row->pdf_comment),
            $row->expiration_date?->format('d/m/Y'),
            $row->custom_created_at?->format('d/m/Y'),
            $row->accepted_at?->format('d/m/Y'),
            $row->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
