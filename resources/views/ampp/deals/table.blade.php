<x-layouts.ampp :title="__('Deals')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All deals - table') }}</x-ui.page-title>

        <div class="d-flex gap-2">
            <a href="{{ action(\App\Http\Controllers\Ampp\Deals\IndexDealBoardController::class) }}" class="link-secondary">
                {{ __('Board view') }}
            </a>

            <a href="{{ action(\App\Http\Controllers\Ampp\Deals\IndexDealTableController::class) }}">
                {{ __('Table view') }}
            </a>
        </div>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="deal-table" :placeholder="__('Search deals...')" />

            <div class="dataTable-filters">

            </div>
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="deal-table" :redirect="action(\App\Http\Controllers\Ampp\Deals\ShowDealController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
