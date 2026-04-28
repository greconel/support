<x-layouts.ampp :title="__('Users')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All users') }}</x-ui.page-title>

        <x-ui.split-button>
            <x-slot name="button">
                <a class="btn btn-primary" href="{{ action(\App\Http\Controllers\Admin\Users\CreateUserController::class) }}">
                    {{ __('Create new user') }}
                </a>
            </x-slot>

            <x-slot name="dropdown">
                <li>
                    <a href="{{ action(\App\Http\Controllers\Admin\Users\ExportUsersController::class) }}"  class="dropdown-item">
                        {{ __('Export users') }}
                    </a>
                </li>
            </x-slot>
        </x-ui.split-button>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="user-table" :placeholder="__('Search users...')" />

            <div class="dataTable-filters">
                <x-table.filters-option state="role" value="ampp user" :count="$adminCount">
                    {{ __('AMPP user') }}
                </x-table.filters-option>

                <x-table.filters-option state="role" value="client" :count="$clientCount">
                    {{ __('Client') }}
                </x-table.filters-option>

                <x-table.filters-option state="archive" value="true" :count="$archiveCount">
                    {{ __('Archive') }}
                </x-table.filters-option>
            </div>
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="user-table" :redirect="action(\App\Http\Controllers\Admin\Users\ShowUserController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
