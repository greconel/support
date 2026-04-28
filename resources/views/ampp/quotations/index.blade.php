<x-layouts.ampp :title="__('Quotations')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All quotations') }}</x-ui.page-title>

        <x-ui.split-button>
            <x-slot name="button">
                <a href="{{ action(\App\Http\Controllers\Ampp\Quotations\CreateQuotationController::class) }}" class="btn btn-primary">
                    {{ __('Create new quotation') }}
                </a>
            </x-slot>

            <x-slot name="dropdown">
                <li>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Quotations\ExportQuotationsController::class) }}" class="dropdown-item">
                        {{ __('Export quotations') }}
                    </a>
                </li>
            </x-slot>
        </x-ui.split-button>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="quotation-table" :placeholder="__('Search quotations...')" />

            <div class="dataTable-filters">
                @foreach(\App\Enums\QuotationStatus::cases() as $status)
                    <x-table.filters-option state="status" value="{{ $status->value }}" :count="\App\Models\Quotation::where('status', '=', $status)->count()">
                        {{ $status->label()  }}
                    </x-table.filters-option>
                @endforeach
            </div>
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="quotation-table" :redirect="action(\App\Http\Controllers\Ampp\Quotations\ShowQuotationController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
