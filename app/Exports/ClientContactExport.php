<?php

namespace App\Exports;

use App\Models\ClientContact;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientContactExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        return ClientContact::all();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Client ID'),
            __('Client name'),
            __('First name'),
            __('Last name'),
            __('Email'),
            __('Phone'),
            __('Description'),
            __('Tags'),
            __('Created at'),
            __('Last updated at'),
        ];
    }

    /** @var ClientContact $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->client_id,
            $row->client->full_name,
            $row->first_name,
            $row->last_name,
            $row->email,
            $row->phone,
            strip_tags($row->description),
            implode(', ', $row->tags),
            $row->created_at?->format('d/m/Y H:i'),
            $row->updated_at?->format('d/m/Y H:i'),

        ];
    }
}
