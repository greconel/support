<x-layouts.ampp :title="__('Activity logs')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All activity logs') }}</x-ui.page-title>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="activity-table" :placeholder="__('Search activities...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="activity-table" :redirect="action(\App\Http\Controllers\Admin\ActivityLogs\ShowActivityLogController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
