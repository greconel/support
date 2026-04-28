<x-layouts.ampp :title="__('GDPR registers')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All GDPR registers') }}</x-ui.page-title>

        <a href="{{ action(\App\Http\Controllers\Ampp\GdprRegisters\CreateGdprRegisterController::class) }}" class="btn btn-primary">
            {{ __('Create new GDPR register') }}
        </a>
    </div>

    <div class="mb-4">
        {{ __('From 25 May 2018, according to the GDPR, you must keep a Register for Processing Activities. You will have to be able to submit the register at the request of the Privacy Commission, so that it can monitor your processing activities.') }}
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="gdprRegister-table" :placeholder="__('Search GDPR registers...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="gdprRegister-table" :redirect="action(\App\Http\Controllers\Ampp\GdprRegisters\EditGdprRegisterController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
