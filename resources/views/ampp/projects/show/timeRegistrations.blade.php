<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('showProjectTable', $project)">
    <x-ampp.projects.layout :project="$project">
        <div class="dataTable-header">
            <x-table.filter table-id="projectTimeRegistration-table" :placeholder="__('Search time registrations...')" />
        </div>

        <div
            @class([
                'row mb-4',
                'justify-content-between' => auth()->user()->hasAnyRole(['super admin', 'PM']),
                'justify-content-end' => ! auth()->user()->hasAnyRole(['super admin', 'PM']),
            ])
        >
            @can('view other users time registrations')
                <div
                    x-data="{
                        allUsers: {{ json_encode($project->users->pluck('name', 'id')->toArray()) }},
                        users: {{ json_encode([auth()->id()]) }},
                        currentUser: {{ auth()->id() }},
                        checkAll: true,
                        select: null,
                        refreshTable() {
                            const url = new URL(window.location.href);

                            _.forEach(this.users, (user) => url.searchParams.append('users[]', user));

                            $('#projectTimeRegistration-table').DataTable().ajax.url(url.href).load();
                        }
                    }"
                    x-init="
                        select = new TomSelect($refs.users, {
                            plugins: ['checkbox_options']
                        });

                        $watch('users', () => refreshTable());

                        $watch('checkAll', function() {
                            if (checkAll){
                                select.clear();

                                _.forEach(allUsers, function(name, value) {
                                    select.addItem(value);
                                })
                            }

                            if (! checkAll){
                                select.clear();
                                select.addItem(currentUser);
                            }

                            select.refreshItems();
                        });
                    "
                    class="col-md-6"
                >
                    <div class="input-group">
                        <div class="input-group-text">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" x-model="checkAll" id="allUsers">
                                <label class="form-check-label" for="allUsers">{{ __('All') }}</label>
                            </div>
                        </div>

                        <x-forms.select
                            name="user_id"
                            :options="$project->users->pluck('name', 'id')->toArray()"
                            :values="$project->users->pluck('id')->toArray()"
                            multiple
                            x-model="users"
                            x-ref="users"
                        />
                    </div>
                </div>
            @endcan
            @if (auth()->user()->can('view other users time registrations'))
            <div class="col-md-6 d-flex justify-content-end gap-3">
                <div>
                    <a
                        href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\ExportTimeRegistrationsForProjectController::class, $project) }}"
                        class="btn btn-primary"
                    >
                        {{ __('Export all time registrations') }}
                    </a>
                </div>
            </div>
            @else
            <div class="col-md-6 d-flex justify-content-end gap-3">
                <div>
                    <a
                        href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\ExportTimeRegistrationsController::class, ['user' => auth()->id(), 'project' => $project->id]) }}"
                        class="btn btn-primary"
                    >
                        {{ __('Export time registrations') }}
                    </a>
                </div>
            </div>
            @endif
        </div>

        {{ $dataTable->table() }}
    </x-ampp.projects.layout>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        @can('viewOtherUsers', \App\Models\TimeRegistration::class)
            <script>
                $(document).ready(function() {
                    $('#projectTimeRegistration-table tbody').on('dblclick', 'td', function (e) {
                        const userId = $(this).closest('tr').data('user-id');
                        const date = $(this).closest('tr').data('date');

                        let url = new URL('{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class) }}');

                        url.searchParams.set('user', userId);
                        url.searchParams.set('date', date);

                        window.location = url.href;
                    });
                });
            </script>
        @endcan
    </x-push>
</x-layouts.ampp>
