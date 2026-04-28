<?php

namespace App\View\Components\Ampp\ProjectReports;

use App\Models\TimeRegistration;
use App\Traits\TimeConversionTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class TimeRegistrationTable extends Component
{
    use TimeConversionTrait;

    public function __construct(
        public Collection $timeRegistrations
    ) {}

    public function calculateHours(TimeRegistration $timeRegistration): string
    {
        return $this->secondsToHoursAndMinutes($timeRegistration->total_time_in_seconds);
    }

    public function render(): View
    {
        return view('components.ampp.project-reports.time-registration-table');
    }
}
