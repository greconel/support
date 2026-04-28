<x-layouts.ampp :title="__('Time registrations - day')">
    <div class="container">
        <div class="page-header">
            <x-ui.page-title>{{ __('Time registrations - day') }}</x-ui.page-title>
        </div>

        <livewire:ampp.time-registrations.day-view />

        <x-push name="modals">
            <livewire:ampp.time-registrations.create-modal />
            <livewire:ampp.time-registrations.edit-modal />
        </x-push>
    </div>
</x-layouts.ampp>
