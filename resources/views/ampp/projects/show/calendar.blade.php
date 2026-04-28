<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('showProjectCalendar', $project)">
    <x-ampp.projects.layout :project="$project">
        <header class="d-flex justify-content-between mb-4">
            <span
                class="fs-3 ms-3"
                x-data="{ date: format(calendar.getDate(), 'MMMM yyyy') }"
                @change-date.window="date = format(calendar.getDate(), 'MMMM yyyy')"
                x-text="date"
            ></span>

            <div class="d-flex gap-4">
                @can('viewOtherUsers', \App\Models\TimeRegistration::class)
                    <div
                        x-data
                        x-init="
                            new TomSelect($refs.users, {
                                onChange: function(value) {
                                    userId = value;
                                    console.log(userId);
                                    calendar.refetchEvents();
                                }
                            });
                        "
                        style="min-width: 15rem"
                    >
                        <x-forms.select name="user_id" :options="$project->users->pluck('name', 'id')->toArray()" :values="auth()->id()" x-ref="users" />
                    </div>
                @endcan

                <div
                    x-data="{ datepicker: null }"

                    x-init="
                        datepicker = flatpickr($refs.datepicker, {
                            plugins: [new monthSelectPlugin({ shorthand: true })],
                            wrap: true,
                            positionElement: $refs.button,
                            disableMobile: true,
                            onChange: (selectedDates) => calendar.gotoDate(selectedDates[0])
                        });
                    "

                    @change-date.window="datepicker.setDate(calendar.getDate())"
                >
                    <div class="flatpickr" x-ref="datepicker" id="datepicker">
                        <input type="hidden" class="d-none" data-input>

                        <button class="btn btn-gray-600" x-ref="button" id="datepickerButton" data-toggle>
                            <i class="fas fa-calendar"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <div id="calendar"></div>
    </x-ampp.projects.layout>

    <x-push name="scripts">
        <script>
            let userId = {{ auth()->id() }};

            const sourceByHoursInDay = {
                events: (info, successCallback, failureCallback) => {
                    axios
                        .get('/api/internal/time-registrations/events', {
                            params: {
                                start: info.startStr,
                                end: info.endStr,
                                projectId: '{{ $project->id }}',
                                userId: userId,
                                countHoursByDay: true
                            }
                        })
                        .then(response => successCallback(response.data.events))
                        .catch(error => failureCallback(error))
                    ;
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
                    const sources = calendar.getEventSources();

                    sources.forEach(source => {
                        source.remove();
                    })

                    calendar.addEventSource(sourceByHoursInDay);

                    window.dispatchEvent(new CustomEvent('change-date'));
                },
                dateClick: (info) => {
                    const url = new URL('{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class) }}');
                    url.searchParams.set('date', format(info.date, 'yyyy-MM-dd'));
                    window.location = url;
                },
                firstDay: 1,
                lazyFetching: false,
            });

            calendar.render();
        </script>
    </x-push>
</x-layouts.ampp>
