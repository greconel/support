<x-layouts.ampp :title="__('Client contacts')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All client contacts') }}</x-ui.page-title>

        <x-ui.split-button>
            <x-slot name="button">
                <button class="btn btn-primary" x-data @click="Livewire.dispatch('showModal')">
                    {{ __('Create new contact') }}
                </button>
            </x-slot>

            <x-slot name="dropdown">
                <li>
                    <a href="{{ action(\App\Http\Controllers\Ampp\ClientContacts\ExportClientContactsController::class) }}" class="dropdown-item">
                        {{ __('Export client contacts') }}
                    </a>
                </li>
            </x-slot>
        </x-ui.split-button>


    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="clientContact-table" :placeholder="__('Search contacts...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="modals">
        <livewire:ampp.client-contacts.create-modal />
    </x-push>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="clientContact-table" :redirect="action(\App\Http\Controllers\Ampp\ClientContacts\ShowClientContactController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
