<x-layouts.ampp :title="__('Time registrations - day')">
    <div class="container">
        <div class="page-header">
            <x-ui.page-title>{{ __('Time registrations - table') }}</x-ui.page-title>
        </div>

        <livewire:ampp.time-registrations.table-view />

        <div class="card card-body">
            <div class="dataTable-header">
                <x-table.filter table-id="timeRegistration-table" :placeholder="__('Search time registrations...')" />
            </div>

            {{ $dataTable->table() }}
        </div>
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <script>
            Livewire.on('refreshTable', function (userId, projectId) {
                const url = new URL(window.location.href);
                url.searchParams.set('user', userId);
                url.searchParams.set('project', projectId);

                $('#timeRegistration-table').DataTable().ajax.url(url.href).load();
            })
        </script>

        @can('viewOtherUsers', \App\Models\TimeRegistration::class)
            <script>
                $(document).ready(function() {
                    $('#timeRegistration-table tbody').on('dblclick', 'td', function (e) {
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
