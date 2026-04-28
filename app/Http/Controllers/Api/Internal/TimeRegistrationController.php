<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Models\TimeRegistration;
use App\Traits\TimeConversionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class TimeRegistrationController extends Controller
{
    use TimeConversionTrait;

    public function events(Request $request)
    {
        if (auth()->user()->can('viewOtherUsers', TimeRegistration::class)){
            $timeRegistrations = TimeRegistration::where('user_id', $request->input('userId', auth()->id()));
        }
        else{
            $timeRegistrations = auth()->user()->timeRegistrations();
        }

        $timeRegistrations = $timeRegistrations->whereBetween('start', [
            Carbon::parse($request->input('start')),
            Carbon::parse($request->input('end'))
        ])->get();

        if ($projectId = $request->input('projectId')){
            $timeRegistrations = $timeRegistrations->where('project_id', $projectId);
        }

        if (! $request->has('countHoursByDay')){
            return response([
                'total_time_in_seconds' => $timeRegistrations->sum('total_time_in_seconds'),
                'events' => $timeRegistrations->pluck('full_calendar_event')
            ]);
        }

        // count all time registrations hours by day

        $timeRegistrationsPerDay = $timeRegistrations->groupBy(function (TimeRegistration $q){
            return Carbon::parse($q->start)->format('d/m/Y');
        });

        $timeRegistrations = collect();

        foreach ($timeRegistrationsPerDay as $timeRegistrationsInDay) {
            $seconds = $timeRegistrationsInDay->sum('total_time_in_seconds');

            // get amount of seconds of a workday

            $workDaySeconds = now()->setTime(7, 36)->diffInSeconds(now()->startOfDay(), absolute: true);

            if ($seconds >= $workDaySeconds) {
                // worked more than a workday
                $backgroundColor = '#306618';
            } else {
                // worked less than a workday
                $backgroundColor = 'red';
            }

            $timeRegistrations->push([
                'title' => $this->secondsToHoursAndMinutes($seconds),
                'start' => $timeRegistrationsInDay->first()->start,
                'end' => $timeRegistrationsInDay->last()->end,
                'allDay' => true,
                'backgroundColor' => $backgroundColor,
                'total_time_in_seconds' => $seconds
            ]);
        }

        return response([
            'total_time_in_seconds' => $timeRegistrations->sum('total_time_in_seconds'),
            'events' => $timeRegistrations
        ]);
    }

    public function personalTime(Request $request)
    {
        $this->validate($request, [
            'projectId' => ['required', 'int', Rule::in(auth()->user()->projects->pluck('id'))],
            'month' => ['required', 'date_format:m/Y']
        ]);

        $personalTime = auth()->user()->timeRegistrations()
            ->where('project_id', $request->input('projectId'))
            ->whereMonth('start', Carbon::createFromFormat('m/Y', $request->input('month')))
            ->sum('total_time_in_seconds');

        return response([
            'personal_time' => $personalTime
        ]);
    }
}
