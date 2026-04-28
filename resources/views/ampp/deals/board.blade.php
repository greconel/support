<x-layouts.ampp :title="__('Leads')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All deals - board') }}</x-ui.page-title>

        <div class="d-flex gap-2">
            <a href="{{ action(\App\Http\Controllers\Ampp\Deals\IndexDealBoardController::class) }}">
                {{ __('Board view') }}
            </a>

            <a href="{{ action(\App\Http\Controllers\Ampp\Deals\IndexDealTableController::class) }}" class="link-secondary">
                {{ __('Table view') }}
            </a>
        </div>
    </div>



    <livewire:ampp.deals.board />

    <x-push name="modals">
        <livewire:ampp.deals.create-deal-modal />
    </x-push>

    <x-push name="styles">
        <style>
            .content > div {
                height: 100%;
            }
        </style>
    </x-push>
</x-layouts.ampp>
