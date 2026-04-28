<?php

namespace App\Exports;

use App\Models\Supplier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupplierExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        return Supplier::all();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('First name'),
            __('Last name'),
            __('Company'),
            __('VAT'),
            __('Iban'),
            __('Email'),
            __('Phone'),
            __('Street'),
            __('Number'),
            __('Postal'),
            __('City'),
            __('Country'),
            __('Country code'),
            __('Notes'),
            __('Created at'),
            __('Last updated at'),
        ];
    }

    /** @var Supplier $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->first_name,
            $row->last_name,
            $row->company,
            $row->vat,
            $row->iban,
            $row->email,
            $row->phone,
            $row->street,
            $row->number,
            $row->postal,
            $row->city,
            $row->country,
            $row->country_code,
            strip_tags($row->notes),
            $row->created_at?->format('d/m/Y H:i'),
            $row->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
