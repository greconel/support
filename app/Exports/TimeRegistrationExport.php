<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\TimeRegistration;
use App\Models\User;
use App\Traits\TimeConversionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimeRegistrationExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    use TimeConversionTrait;

    public function __construct(
        public ?User $user = null,
        public ?Project $project = null
    ) {}


    public function collection(): Collection
    {
        return TimeRegistration::when($this->user, fn(Builder $query) => $query->where('user_id', '=', $this->user->id))
            ->when($this->project, fn(Builder $query) => $query->where('project_id', '=', $this->project->id))
            ->get();
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('User ID'),
            __('User name'),
            __('Project ID'),
            __('Project name'),
            __('Start'),
            __('End'),
            __('Total time in seconds'),
            __('Total hours'),
            __('Activity'),
            __('Description'),
            __('Is billable'),
            __('Is billed'),
        ];
    }

    /** @var TimeRegistration $row */
    public function map($row): array
    {
        $totalHours = $this->secondsToHoursAndMinutes($row->total_time_in_seconds);

        return [
            $row->id,
            $row->user_id,
            $row->user->name,
            $row->project_id,
            $row->project?->name,
            $row->start?->format('d/m/Y H:i'),
            $row->end?->format('d/m/Y H:i'),
            $row->total_time_in_seconds,
            $totalHours,
            $row->projectActivity?->name,
            strip_tags($row->description),
            $row->is_billable ? __('Yes') : __('No'),
            $row->is_billed ? __('Yes') : __('No'),
        ];
    }
}
