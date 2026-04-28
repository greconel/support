<x-layouts.ampp :title="__('QR codes')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All QR codes') }}</x-ui.page-title>

        <a href="{{ action(\App\Http\Controllers\Ampp\QrCodes\CreateQrCodeController::class) }}" class="btn btn-primary">
            {{ __('Create new QR code') }}
        </a>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="qrCode-table" :placeholder="__('Search QR codes...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="qrCode-table" :redirect="action(\App\Http\Controllers\Ampp\QrCodes\ShowQrCodeController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
