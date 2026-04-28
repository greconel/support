<?php

namespace App\View\Components\Ampp\ProjectReports;

use App\Models\Client;
use App\Traits\TimeConversionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ClientTable extends Component
{
    use TimeConversionTrait;

    public function __construct(
        public Collection $clients,
        public Carbon $from,
        public Carbon $till
    ) {}

    public function calculateHours(Client $client): string
    {
        $seconds = $client
            ->timeRegistrations()
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function calculateBillableHours(Client $client): string
    {
        $seconds = $client
            ->timeRegistrations()
            ->whereBetween('start', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->where('is_billable', '=', true)
            ->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function render(): View
    {
        return view('components.ampp.project-reports.client-table');
    }
}
