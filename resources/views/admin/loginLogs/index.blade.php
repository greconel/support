<x-layouts.ampp :title="__('Login logs')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All login logs') }}</x-ui.page-title>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="loginLog-table" :placeholder="__('Search login logs...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="loginLog-table" :redirect="action(\App\Http\Controllers\Admin\LoginLogs\ShowLoginLogController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
