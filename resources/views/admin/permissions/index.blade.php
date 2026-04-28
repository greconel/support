<x-layouts.ampp :title="__('Permissions')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All permissions') }}</x-ui.page-title>

        <a href="{{ action(\App\Http\Controllers\Admin\Permissions\CreatePermissionController::class) }}" class="btn btn-primary">
            {{ __('Create new permission') }}
        </a>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="permission-table" :placeholder="__('Search permissions...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="permission-table" :redirect="action(\App\Http\Controllers\Admin\Permissions\EditPermissionController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
