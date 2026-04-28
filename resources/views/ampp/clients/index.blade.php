<x-layouts.ampp :title="__('Clients')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All clients') }}</x-ui.page-title>

        <x-ui.split-button>
            <x-slot name="button">
                <a href="{{ action(\App\Http\Controllers\Ampp\Clients\CreateClientController::class) }}" class="btn btn-primary">
                    {{ __('Create new client') }}
                </a>
            </x-slot>

            <x-slot name="dropdown">
                <li>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Clients\ExportClientsController::class) }}" class="dropdown-item">
                        {{ __('Export clients') }}
                    </a>
                </li>
            </x-slot>
        </x-ui.split-button>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="client-table" :placeholder="__('Search clients...')" />

            <div class="dataTable-filters">
                @foreach(\App\Enums\ClientType::cases() as $type)
                    <x-table.filters-option state="type" value="{{ $type->value }}" :count="\App\Models\Client::where('type', '=', $type)->count()">
                        {{ $type->label()  }}
                    </x-table.filters-option>
                @endforeach

                <x-table.filters-option state="archive" value="true" :count="$archiveCount">
                    {{ __('Archive') }}
                </x-table.filters-option>
            </div>
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="client-table" :redirect="action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
