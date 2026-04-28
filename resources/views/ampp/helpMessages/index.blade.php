<x-layouts.ampp :title="__('My help messages')">
    <div class="container">
        <x-ui.page-title>{{ __('My help messages') }}</x-ui.page-title>

        <div class="card">
            <div class="card-body">
                <x-tabs.tab id="help">
                    <x-slot name="headers">
                        <x-tabs.header headerId="message-tab" target="message" class="active">{{ __('New message') }}</x-tabs.header>
                        <x-tabs.header headerId="index-tab" target="index">{{ __('All messages') }}</x-tabs.header>
                    </x-slot>

                    <x-slot name="content">
                        <x-tabs.content id="message" class="show active">
                            <livewire:ampp.help-center />
                        </x-tabs.content>

                        <x-tabs.content id="index">
                            <div class="dataTable-header">
                                <x-table.filter table-id="helpMessage-table" :placeholder="__('Search help messages...')" />
                            </div>

                            {{ $dataTable->table() }}
                        </x-tabs.content>
                    </x-slot>
                </x-tabs.tab>
            </div>
        </div>
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="helpMessage-table" :redirect="action(\App\Http\Controllers\Ampp\HelpMessages\ShowHelpMessageController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
