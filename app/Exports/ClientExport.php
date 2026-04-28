<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        return Client::withTrashed()->get();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('First name'),
            __('Last name'),
            __('Company'),
            __('Email'),
            __('Phone'),
            __('VAT'),
            __('Street'),
            __('Number'),
            __('Postal'),
            __('City'),
            __('Country'),
            __('Country code'),
            __('Latitude'),
            __('Longitude'),
            __('Description'),
            __('Created at'),
            __('Last updated at'),
            __('Archived'),
            __('Archived at'),
        ];
    }

    /** @var Client $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->first_name,
            $row->last_name,
            $row->company,
            $row->email,
            $row->phone,
            $row->vat,
            $row->street,
            $row->number,
            $row->postal,
            $row->city,
            $row->country,
            $row->country_code,
            $row->lat,
            $row->lng,
            strip_tags($row->description),
            $row->created_at?->format('d/m/Y H:i'),
            $row->updated_at?->format('d/m/Y H:i'),
            $row->deleted_at ? __('Yes') : __('No'),
            $row->deleted_at?->format('d/m/Y H:i'),
        ];
    }
}
