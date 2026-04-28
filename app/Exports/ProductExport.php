<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Name'),
            __('description'),
            __('price'),
            __('Created at'),
            __('Last updated at'),
        ];
    }

    /** @var Product $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            strip_tags($row->description),
            $row->price,
            $row->created_at?->format('d/m/Y H:i'),
            $row->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
