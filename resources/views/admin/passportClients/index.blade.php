<x-layouts.ampp :title="__('Passport clients')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All passport clients') }}</x-ui.page-title>

        <a href="{{ action(\App\Http\Controllers\Admin\PassportClients\CreatePassportClientController::class) }}" class="btn btn-primary">
            {{ __('Create new passport client') }}
        </a>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="passportClient-table" :placeholder="__('Search passport client...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row
            id="passportClient-table"
            :redirect="action(\App\Http\Controllers\Admin\PassportClients\EditPassportClientController::class, ':id')"
        />
    </x-push>
</x-layouts.ampp>
