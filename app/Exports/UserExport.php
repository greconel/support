<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection(): Collection
    {
        return User::withTrashed()->get();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Name'),
            __('Email'),
            __('Last active at'),
            __('Created at'),
            __('Last updated at'),
            __('Archived'),
            __('Archived at')
        ];
    }

    /** @var User $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
            $row->last_active_at?->format('d/m/Y H:i'),
            $row->created_at?->format('d/m/Y H:i'),
            $row->updated_at?->format('d/m/Y H:i'),
            $row->deleted_at ? __('Yes') : __('No'),
            $row->deleted_at?->format('d/m/Y H:i'),
        ];
    }
}
