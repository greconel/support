<x-layouts.ampp :title="__('Roles')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All roles') }}</x-ui.page-title>

        <a href="{{ action(\App\Http\Controllers\Admin\Roles\CreateRoleController::class) }}" class="btn btn-primary">
            {{ __('Create new role') }}
        </a>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="role-table" :placeholder="__('Search roles...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="role-table" :redirect="action(\App\Http\Controllers\Admin\Roles\EditRoleController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
