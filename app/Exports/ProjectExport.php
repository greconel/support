<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\User;
use App\Traits\TimeConversionTrait;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    use TimeConversionTrait;

    public function __construct(
        public User $user
    ){}

    public function collection(): Collection
    {
        return $this->user->projects()->get();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Client ID'),
            __('Client name'),
            __('Client company'),
            __('Name'),
            __('Color'),
            __('Budget in money'),
            __('Budget in hours'),
            __('Total hours worked in'),
            __('Description'),
            __('Created at'),
            __('Last updated at'),
            __('Archived'),
            __('Archived at'),
        ];
    }

    /** @var Project $row */
    public function map($row): array
    {
        $totalHours = $this->secondsToHoursAndMinutes($row->timeRegistrations()->sum('total_time_in_seconds'));

        return [
            $row->id,
            $row->client_id,
            $row->client?->full_name,
            $row->client?->company,
            $row->name,
            $row->color,
            $row->budget_money,
            $row->budget_hours,
            $totalHours,
            strip_tags($row->description),
            $row->created_at?->format('d/m/Y H:i'),
            $row->updated_at?->format('d/m/Y H:i'),
            $row->deleted_at ? __('Yes') : __('No'),
            $row->deleted_at?->format('d/m/Y H:i'),
        ];
    }
}
