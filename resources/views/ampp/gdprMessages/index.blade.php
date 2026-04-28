<x-layouts.ampp :title="__('GDPR messages')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All GDPR messages') }}</x-ui.page-title>

        <a href="{{ action(\App\Http\Controllers\Ampp\GdprMessages\CreateGdprMessageController::class) }}" class="btn btn-primary">
            {{ __('Create new GDPR message') }}
        </a>
    </div>

    <div class="mb-4">
        {!! __('The reason reporting tracking is a good practice is twofold. First of all, this can be a useful document to monitor the number of data breaches and to draw consequences for the future. In addition, it can be a useful document to submit to the Privacy Commission to demonstrate that you deal with data breaches consciously and possibly to demonstrate your motivation for failing to report the data breach.<br><br>Here you can you can make reports to the Privacy Commission: <a href="https://eforms.gegevensbeschermingsautoriteit.be/privacy-commission/home/public/upload" target="_blank">https://eforms.gegevensbeschermingsautoriteit.be/privacy-commission/home/public/upload</a>') !!}
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="gdprMessage-table" :placeholder="__('Search GDPR messages...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="gdprMessage-table" :redirect="action(\App\Http\Controllers\Ampp\GdprMessages\EditGdprMessageController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
