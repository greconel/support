<div>
    <header class="row justify-content-center justify-content-lg-between align-items-center mb-4">
        <div class="col-lg-6 d-flex flex-column flex-md-row justify-content-center justify-content-lg-start align-items-center gap-2 mb-2 mb-lg-0">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-gray-600 px-3" wire:click="previousMonth">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <button type="button" class="btn btn-gray-600 px-3" wire:click="nextMonth">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <span
                class="fs-3 ms-3"
                x-data="{ date: format(calendar.getDate(), 'MMMM yyyy') }"
                @change-date.window="date = format(calendar.getDate(), 'MMMM yyyy')"
                x-text="date"
            ></span>
        </div>

        <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end align-items-center gap-2">
            @if(! $date->isCurrentMonth())
                <button class="btn btn-gray-600" wire:click="today">{{ __('This month') }}</button>
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
                            onChange: function(selectedDates, dateStr) {
                                $wire.setDate(dateStr);
                                calendar.gotoDate(dateStr);
                            }
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
                    href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexWeekTimeRegistrationController::class,  ['date' => $date->format('Y-m-d'), 'user' => $userId]) }}"
                    class="btn btn-gray-600"
                >
                    {{ __('Week') }}
                </a>

                <a
                    href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexMonthTimeRegistrationController::class) }}"
                    class="btn btn-gray-600 active"
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

        <div class="d-flex justify-content-end gap-3 mt-4">
            @can('viewOtherUsers', \App\Models\TimeRegistration::class)
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
            @endcan

            <div
                x-data="{ projectId: $wire.entangle('projectId').live, projects: $wire.entangle('projects'), select: null }"
                x-init="
                    select = new TomSelect($refs.projects, { allowEmptyOption: true, hidePlaceholder: true });

                    _.forEach(projects, function(project) {
                        select.addOption({ value: project['value'], text: project['name'] })
                    })

                    select.addItem(projectId ?? '');

                    $watch('projects', function() {
                        select.clear();
                        select.clearOptions();

                        _.forEach(projects, function(project) {
                            select.addOption({ value: project['value'], text: project['name'] })
                        })

                        select.addItem('');
                    })
                "
                style="min-width: 15rem"
                wire:ignore
            >
                <select class="form-select" x-model="projectId" x-ref="projects"></select>
            </div>
        </div>
    </header>

    <div id="calendar" wire:ignore></div>
</div>

<x-push name="scripts">
    <script>
        let userId = {{ $userId }};
        let projectId = {{ $projectId ?? 'null' }};

        const sourceByHoursInDay = {
            events: (info, successCallback, failureCallback) => {
                axios.get('/api/internal/time-registrations/events', {
                    params: {
                        start: info.startStr,
                        end: info.endStr,
                        countHoursByDay: true,
                        userId,
                        projectId
                    }
                }).then(response => {
                    successCallback(response.data.events)
                }).catch(error => {
                    failureCallback(error)
                });
            },
            textColor: 'white',
            display: 'background',
            classNames: ['text-light']
        };

        const calendar = new Calendar(document.getElementById('calendar'), {
            plugins: [daygridPlugin, momentPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            height: 800,
            headerToolbar: {
                start: '',
                center: '',
                end: '',
            },
            datesSet: (info) => {
                calendar.removeAllEvents();
                calendar.removeAllEventSources();
                calendar.addEventSource(sourceByHoursInDay);

                window.dispatchEvent(new CustomEvent('change-date'));
            },
            dateClick: (info) => {
                const url = new URL('{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class) }}');
                url.searchParams.set('date', format(info.date, 'yyyy-MM-dd'));
                url.searchParams.set('user', userId);
                window.location = url;
            },
            initialDate: '{{ $date->format('Y-m-d') }}',
            firstDay: 1,
            lazyFetching: false,
        });

        calendar.render();

        Livewire.on('updateCalendarDate', (date) => {
            calendar.gotoDate(date);
            calendar.refetchEvents();
        });

        Livewire.on('updateCalendarUser', (id) => {
            userId = id;
            calendar.refetchEvents();
        });

        Livewire.on('updateCalendarProject', (id) => {
            projectId = id ?? null;
            calendar.refetchEvents();
        });
    </script>
</x-push>
