<x-layouts.ampp :title="__('Suppliers')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All suppliers') }}</x-ui.page-title>

        <x-ui.split-button>
            <x-slot name="button">
                <a href="{{ action(\App\Http\Controllers\Ampp\Suppliers\CreateSupplierController::class) }}" class="btn btn-primary">
                    {{ __('Create new supplier') }}
                </a>
            </x-slot>

            <x-slot name="dropdown">
                <li>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Suppliers\ExportSupplierController::class) }}" class="dropdown-item">
                        {{ __('Export suppliers') }}
                    </a>
                </li>
            </x-slot>
        </x-ui.split-button>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="supplier-table" :placeholder="__('Search suppliers...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="supplier-table" :redirect="action(\App\Http\Controllers\Ampp\Suppliers\ShowSupplierController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
