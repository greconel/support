<div>
    <header class="row justify-content-center justify-content-lg-between align-items-center mb-4">
        <div class="col-lg-6 d-flex flex-column flex-md-row justify-content-center justify-content-lg-start align-items-center gap-2 mb-2 mb-lg-0">
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
                    <b>{{ __('Today:') }}</b>
                @endif

                {{ $date->format('l, d M Y') }}
            </span>
        </div>

        <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end align-items-center gap-2">
            @if(! $date->isToday())
                <button class="btn btn-gray-600" wire:click="today">{{ __('Today') }}</button>
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
                    href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class) }}"
                    class="btn btn-gray-600 active"
                >
                    {{ __('Day') }}
                </a>

                <a
                    href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexWeekTimeRegistrationController::class, ['date' => $date->format('Y-m-d'), 'user' => $userId]) }}"
                    class="btn btn-gray-600"
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

    <section class="time-tracker-day-week mb-4" style="overflow-x: auto">
        @foreach($this->daysOfWeek as $day)
            <x-ampp.time-registrations.day-card
                :day="$day->dayName"
                :time="$this->calculateTimeForDay($day)"
                :active="$day->format('Y-m-d') == $date->format('Y-m-d')"
                :runningTimeRegistration="auth()->user()->timeRegistrations()->whereDate('start', $day)->whereNull('end')->first()"
                wire:click="setDate('{{ $day->format('Y-m-d') }}')"
            />
        @endforeach

        <x-ampp.time-registrations.day-card
            day="Total"
            :time="$this->calculateTimeForWeek()"
            total
        />
    </section>

    <section class="d-flex justify-content-center gap-4 mb-4">
        @if($date->isToday())
            <button class="btn btn-success" wire:click="startNewTimer">
                {{ __('Start new timer') }}

                @if($userId != auth()->id())
                    {{ __('(for you)') }}
                @endif
            </button>
        @endif

        <button class="btn btn-primary" wire:click="create">
            {{ __('Add time registration') }}

            @if($userId != auth()->id())
                {{ __('(for the selected user)') }}
            @endif
        </button>
    </section>

    <section class="time-tracker-day-list mb-4">
        @forelse($timeRegistrations as $timeRegistration)
            <livewire:ampp.time-registrations.list-item :time-registration="$timeRegistration" :wire:key="time() . '_' . $timeRegistration->id" />
        @empty
            <div class="text-center text-muted fs-3">{{ __('Wow, such empty!') }}</div>
        @endforelse
    </section>

    @if(count($this->latestProjects) > 0 && $userId == auth()->id())
        <section class="d-none d-md-block time-tracker-action-box mb-4">
            <p class="text-center mb-3 text-muted text-uppercase fs-5">{{ __('Quick actions') }}</p>

            <div class="row mx-0">
                @foreach($this->latestProjects as $latestProject)
                    <div class="col-md-3 px-2 py-2">
                        <div class="time-tracker-action-box-button">
                            <div class="d-flex flex-column align-items-center mb-4" style="max-width: inherit">
                                <hr class="mt-0 mb-1" style="height: 3px; width: 50px; color: {{ $latestProject['color'] }}">

                                <span class="d-inline-block text-truncate" style="max-width: inherit" title="{{ $latestProject['name'] }}">
                                    {{ $latestProject['name'] }}
                                </span>

                                @if($latestProject['client_name'])
                                    <span class="text-muted small d-inline-block text-truncate" style="max-width: inherit" title="{{ $latestProject['client_name'] }}">
                                        ({{ $latestProject['client_name'] }})
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-success" wire:click="startNewTimer({{ $latestProject['id'] }})">
                                    {{ __('Start new timer') }}
                                </button>

                                <button class="btn btn-sm btn-outline-primary" wire:click="create({{ $latestProject['id'] }})">
                                    {{ __('Add time registration') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="d-flex justify-content-center gap-4 mb-6">
        @if($date->isToday())
            <button class="btn btn-success" wire:click="startNewTimer">
                {{ __('Start new timer') }}

                @if($userId != auth()->id())
                    {{ __('(for you)') }}
                @endif
            </button>
        @endif

        <button class="btn btn-primary" wire:click="create">
            {{ __('Add time registration') }}

            @if($userId != auth()->id())
                {{ __('(for the selected user)') }}
            @endif
        </button>
    </section>
</div>
