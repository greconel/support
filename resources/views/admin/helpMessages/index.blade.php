<x-layouts.ampp :title="__('Help messages')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All help messages') }}</x-ui.page-title>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="helpMessage-table" :placeholder="__('Search help messages...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="helpMessage-table" :redirect="action(\App\Http\Controllers\Admin\HelpMessages\ShowHelpMessageController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
