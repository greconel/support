<x-layouts.ampp :title="__('Detailed time report')" :breadcrumbs="Breadcrumbs::render('detailedTimeReport', $from, $till, $name)">
    <div class="page-header">
        <x-ui.page-title>{{ __('Detailed time report for :name', ['name' => $name]) }}</x-ui.page-title>
    </div>

    <div class="row mb-4">
        <div class="col-auto">
            <div class="card card-body py-3 px-4">
                <div class="d-flex flex-wrap gap-4 align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="far fa-clock text-blue-200 fa-lg"></i>
                        <div>
                            <span class="text-gray-500">{{ __('Total') }}</span>
                            <div class="fs-4 fw-bolder">{{ $totalHours }}h {{ $minutes }}m</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-euro-sign text-green-200 fa-lg"></i>
                        <div>
                            <span class="text-gray-500">{{ __('Billable') }}</span>
                            <div class="fs-4 fw-bolder">{{ $totalHoursBillable }}h {{ $minutesBillable }}m</div>
                        </div>
                    </div>

                    @if(count($hoursByProjectAndActivity) > 0)
                        <div class="vr"></div>
                        @foreach($hoursByProjectAndActivity as $projectId => $hoursByActivities)
                            <div>
                                <span class="text-gray-500">{{ \App\Models\Project::find($projectId)?->name }}</span>
                                <div class="d-flex gap-3">
                                    @foreach($hoursByActivities as $projectActivityId => $hours)
                                        <span class="fs-5 fw-bolder" title="{{ \App\Models\ProjectActivity::find($projectActivityId)?->name ?? __('No activity') }}">
                                            <span class="text-gray-500">{{ \App\Models\ProjectActivity::find($projectActivityId)?->name ?? '—' }}:</span>
                                            {{ floor($hours) }}h {{ floor(($hours - floor($hours)) * 60) }}m
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <x-table.filter table-id="detailedTimeReport-table" :placeholder="__('Search time registrations...')" />

            <button type="button" class="btn btn-primary btn-sm" id="btn-process-invoicing" style="display: none;">
                <i class="fas fa-file-invoice me-1"></i> {{ __('Verwerk voor facturatie') }}
            </button>
        </div>

        {{ $dataTable->table() }}
    </div>

    @livewire('ampp.project-reports.edit-time-registration-modal')

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <script>
            $(document).ready(function() {
                // Double-click to navigate to day view
                $('#detailedTimeReport-table tbody').on('dblclick', 'td:not(:first-child):not(:last-child)', function (e) {
                    const userId = $(this).closest('tr').data('user-id');
                    const date = $(this).closest('tr').data('date');

                    let url = new URL('{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class) }}');

                    url.searchParams.set('user', userId);
                    url.searchParams.set('date', date);

                    window.location = url.href;
                });

                // Edit button click
                $('#detailedTimeReport-table tbody').on('click', '.btn-edit-tr', function (e) {
                    e.stopPropagation();
                    const id = $(this).data('id');
                    Livewire.dispatch('editTimeRegistration', { id: id });
                });

                // Select all checkbox
                $('#detailedTimeReport-table').on('change', '#select-all-tr', function () {
                    const checked = $(this).is(':checked');
                    $('#detailedTimeReport-table .tr-checkbox').prop('checked', checked);
                    toggleProcessButton();
                });

                // Individual checkbox
                $('#detailedTimeReport-table').on('change', '.tr-checkbox', function () {
                    toggleProcessButton();
                });

                function toggleProcessButton() {
                    const count = $('#detailedTimeReport-table .tr-checkbox:checked').length;
                    const btn = $('#btn-process-invoicing');
                    if (count > 0) {
                        btn.show().text('{{ __('Verwerk voor facturatie') }} (' + count + ')');
                    } else {
                        btn.hide();
                    }
                }

                // Process for invoicing button - navigate to preparation page
                $('#btn-process-invoicing').on('click', function () {
                    const ids = [];
                    $('#detailedTimeReport-table .tr-checkbox:checked').each(function () {
                        ids.push(parseInt($(this).val()));
                    });

                    if (ids.length === 0) return;

                    // Create a form and POST to the preparation page
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ action(\App\Http\Controllers\Ampp\ProjectReports\PrepareInvoicingController::class) }}'
                    });

                    form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));

                    ids.forEach(function (id) {
                        form.append($('<input>', { type: 'hidden', name: 'ids[]', value: id }));
                    });

                    $('body').append(form);
                    form.submit();
                });

                // Reload DataTable when time registration is updated from modal
                Livewire.on('timeRegistrationUpdated', function () {
                    window.LaravelDataTables['detailedTimeReport-table'].ajax.reload(null, false);
                });
            });
        </script>
    </x-push>
</x-layouts.ampp>
