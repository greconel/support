<x-layouts.ampp :title="__('Create new QR code')" :breadcrumbs="Breadcrumbs::render('createQrCode')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new QR code') }}</x-ui.page-title>

        <div class="card card-body">
            <livewire:ampp.qr-codes.create />
        </div>
    </div>
</x-layouts.ampp>
