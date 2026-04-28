<x-layouts.ampp :title="__('Invoice categories')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All invoice categories') }}</x-ui.page-title>

        <a href="{{ action(\App\Http\Controllers\Ampp\InvoiceCategories\CreateInvoiceCategoryController::class) }}" class="btn btn-primary">
            {{ __('Create new category') }}
        </a>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="invoice-category-table" :placeholder="__('Search categories...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="invoice-category-table" :redirect="action(\App\Http\Controllers\Ampp\InvoiceCategories\EditInvoiceCategoryController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
