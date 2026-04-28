<x-layouts.ampp :title="__('Products')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All products') }}</x-ui.page-title>

        <x-ui.split-button>
            <x-slot name="button">
                <a href="{{ action(\App\Http\Controllers\Ampp\Products\CreateProductController::class) }}" class="btn btn-primary">
                    {{ __('Create new product') }}
                </a>
            </x-slot>

            <x-slot name="dropdown">
                <li>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Products\ExportProductsController::class) }}" class="dropdown-item">
                        {{ __('Export products') }}
                    </a>
                </li>
            </x-slot>
        </x-ui.split-button>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="product-table" :placeholder="__('Search products...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="product-table" :redirect="action(\App\Http\Controllers\Ampp\Products\EditProductController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
