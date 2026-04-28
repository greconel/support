<div>
    <header class="row justify-content-center justify-content-lg-between align-items-center mb-4">
        <div class="col-lg-7 d-flex flex-column flex-md-row justify-content-center justify-content-lg-start align-items-center gap-2 mb-2 mb-lg-0">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-gray-600 px-3" wire:click="previousWeek">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <button type="button" class="btn btn-gray-600 px-3" wire:click="nextWeek">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <span class="fs-3 ms-3 text-center text-lg-start">
                @if($date->isToday())
                    <b>{{ __('This week:') }}</b>
                @endif

                {{ $date->copy()->startOfWeek()->format('d M Y') }}
                -
                {{ $date->copy()->endOfWeek()->format('d M Y') }}
            </span>
        </div>

        <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end align-items-center gap-2">
            @if(! $date->isCurrentWeek())
                <button class="btn btn-gray-600" wire:click="today">{{ __('This week') }}</button>
            @endif

            <div
                x-data="{
                    date: $wire.entangle('date').live,
                    datepicker: null ,
                    get dateString() { return format(new Date(this.date), 'yyyy-MM-dd') }
                }"

                x-init="
                    datepicker = flatpickr($refs.datepicker, {
                        wrap: true,
                        positionElement: $refs.button,
                        defaultDate: dateString,
                        disableMobile: true,
                        onChange: (selectedDates, dateStr) => $wire.setDate(dateStr)
                    });

                    $watch('date', () => datepicker.setDate(dateString));
                "
            >
                <div class="flatpickr" x-ref="datepicker" wire:ignore>
                    <input type="hidden" class="d-none" data-input>

                    <button class="btn btn-gray-600" x-ref="button" data-toggle>
                        <i class="fas fa-calendar"></i>
                    </button>
                </div>
            </div>

            <div class="btn-group">
                <a
                    href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class, ['date' => $date->format('Y-m-d'), 'user' => $userId]) }}"
                    class="btn btn-gray-600"
                >
                    {{ __('Day') }}
                </a>

                <a
                    href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexWeekTimeRegistrationController::class) }}"
                    class="btn btn-gray-600 active"
                >
                    {{ __('Week') }}
                </a>

                <a
                    href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexMonthTimeRegistrationController::class, ['date' => $date->format('Y-m-d'), 'user' => $userId]) }}"
                    class="btn btn-gray-600"
                >
                    {{ __('Month') }}
                </a>

                <a
                    href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexTableTimeRegistrationController::class, ['date' => $date->format('Y-m-d'), 'user' => $userId]) }}"
                    class="btn btn-gray-600"
                >
                    {{ __('Table') }}
                </a>
            </div>
        </div>

        @can('viewOtherUsers', \App\Models\TimeRegistration::class)
            <div class="d-flex justify-content-end mt-4">
                <div
                    x-data="{ userId: $wire.entangle('userId').live, select: null }"
                    x-init="
                        select = new TomSelect($refs.users, { allowEmptyOption: true, hidePlaceholder: true });
                    "
                    style="min-width: 15rem"
                    wire:ignore
                >
                    <x-forms.select name="user_id" :options="$users" :values="$userId" x-model="userId" x-ref="users" />
                </div>
            </div>
        @endcan
    </header>

    <section class="table-responsive time-tracker-week">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th style="min-width: 300px"></th>

                    @foreach($this->daysOfWeek as $day)
                        <th style="white-space: nowrap">
                            <a
                                href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class, ['date' => $day->format('Y-m-d'), 'user' => $userId]) }}"
                                class="d-flex flex-column"
                            >
                                <span>{{ $day->format('l') }}</span>
                                <span class="fw-normal">{{ $day->format('d M') }}</span>
                            </a>
                        </th>
                    @endforeach

                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse($this->projectIds as $projectId)
                    @php
                        $project = \App\Models\Project::find($projectId);
                    @endphp

                    <tr>
                        <td style="white-space: nowrap">
                            @if($project)
                                <a href="{{ action(\App\Http\Controllers\Ampp\Projects\ShowProjectOverviewController::class, $project) }}" class="text-decoration-none">
                                    <span class="fw-bolder fs-4">{{ $project->name }}</span>

                                    @if($project->client->id)
                                        <span>({{ $project->client->full_name }})</span>
                                    @endif
                                </a>
                            @else
                                <span class="fw-bolder fs-4">{{ __('No project') }}</span>
                            @endif
                        </td>

                        @foreach($this->daysOfWeek as $day)
                            <td>
                                @php
                                    $time = $this->calculateTimeForProjectOnDay($day, $project);
                                @endphp

                                <span @class(['fw-bolder' => $time != '00:00'])>{{ $time }}</span>
                            </td>
                        @endforeach

                        <td>
                            <span class="fw-bolder">{{ $this->calculateTimeForProjectOnWeek($project) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">
                            {{ __('Wow, such empty!') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <tfoot>
                <tr>
                    <th></th>

                    @foreach($this->daysOfWeek as $day)
                        <th>{{ $this->calculateTimeForDay($day) }}</th>
                    @endforeach

                    <th>{{ $this->calculateTimeForWeek() }}</th>
                </tr>
            </tfoot>
        </table>
    </section>
</div>
